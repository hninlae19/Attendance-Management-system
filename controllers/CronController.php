<?php
class CronController extends Controller {
    public function run() {
        // Secure endpoint: allow only CLI or valid token
        $is_cli = php_sapi_name() === 'cli';
        $token = $_GET['token'] ?? '';
        $valid_token = 'cron_secret_12345'; // Hardcoded for this demo, should be in .env or settings

        if (!$is_cli && $token !== $valid_token) {
            die('Access Denied.');
        }
        
        $settingModel = $this->model('Setting');
        $attendanceModel = $this->model('Attendance');
        $settings = $settingModel->getSettings();
        
        $auto_checkout_time = $settings['auto_checkout_time'] ?? '17:30:00';
        $current_time = date('H:i:s');
        $today = date('Y-m-d');
        
        $db = new Database();
        $conn = $db->getConnection();

        $output = [];

        // 1. Auto Check-Out (Handles all past missed checkouts and today's if past deadline)
        $attendanceModel->autoCheckoutIfMissed();
        $output[] = "Auto check-out process executed.";

        // Determine date range for catch-up
        $last_run_file = __DIR__ . '/../config/last_cron_run.txt';
        $dates_to_process = [];
        
        if (file_exists($last_run_file)) {
            $last_run_date = trim(file_get_contents($last_run_file));
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $last_run_date)) {
                $start_date = date('Y-m-d', strtotime($last_run_date . ' +1 day'));
                $current_date = $start_date;
                while ($current_date <= $today) {
                    $dates_to_process[] = $current_date;
                    $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
                }
            } else {
                $dates_to_process = [date('Y-m-d', strtotime('-1 day')), $today];
            }
        } else {
            // First time running, just do yesterday and today
            $dates_to_process = [date('Y-m-d', strtotime('-1 day')), $today];
        }

        require_once __DIR__ . '/../models/Deduction.php';
        $deductionModel = new Deduction();
        $holidayModel = $this->model('Holiday');

        $final_last_run = file_exists($last_run_file) ? trim(file_get_contents($last_run_file)) : date('Y-m-d', strtotime('-2 days'));

        foreach ($dates_to_process as $date) {
            if ($date === $today && $current_time < $auto_checkout_time) {
                continue; // Skip today if it's before the auto checkout time deadline
            }

            $output[] = "--- Processing for date: $date ---";

            // 1.5 Process Late and Half Day Deductions for this date
            $attQuery = "SELECT id, employee_id, status FROM attendance WHERE date = :date AND (status = 'Late' OR status = 'Half Day')";
            $attStmt = $conn->prepare($attQuery);
            $attStmt->bindParam(':date', $date);
            $attStmt->execute();
            $late_half_attendances = $attStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($late_half_attendances as $att) {
                if ($att['status'] === 'Late') {
                    $monthStart = date('Y-m-01', strtotime($date));
                    $monthEnd = date('Y-m-t', strtotime($date));
                    
                    $lateCountStmt = $conn->prepare("SELECT COUNT(*) FROM attendance WHERE employee_id = :emp_id AND status = 'Late' AND date BETWEEN :start AND :end");
                    $lateCountStmt->execute([
                        ':emp_id' => $att['employee_id'],
                        ':start' => $monthStart,
                        ':end' => $monthEnd
                    ]);
                    $lateCount = $lateCountStmt->fetchColumn();
                    
                    if ($lateCount > 0 && $lateCount % 3 == 0) {
                        $deductionModel->applyAutomatedDeduction($att['employee_id'], 'Half Day Absence', $date, 'Penalty for 3 Lates in month', 'Attendance System');
                    }
                } elseif ($att['status'] === 'Half Day') {
                    $deductionModel->applyAutomatedDeduction($att['employee_id'], 'Half Day Absence', $date, 'Automated Half Day Absence Deduction', 'Attendance System');
                }
            }
            $output[] = "Processed Late and Half Day deductions for " . count($late_half_attendances) . " records.";

            // 2. Mark Absent for working days
            if (!$holidayModel->isHoliday($date) && date('N', strtotime($date)) < 6) {
                // Find employees with no attendance and no approved/pending leave
                $empQuery = "SELECT e.id, e.basic_salary FROM employees e 
                             JOIN users u ON e.user_id = u.id
                             WHERE u.status = 'Active' 
                             AND e.id NOT IN (SELECT employee_id FROM attendance WHERE date = :date) 
                             AND e.id NOT IN (SELECT employee_id FROM leave_requests WHERE :date BETWEEN start_date AND end_date AND status IN ('Approved', 'Pending'))";
                $empStmt = $conn->prepare($empQuery);
                $empStmt->bindParam(':date', $date);
                $empStmt->execute();
                $absent_employees = $empStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($absent_employees as $emp) {
                    // Mark Absent
                    $ins = "INSERT INTO attendance (employee_id, date, status) VALUES (:emp_id, :date, 'Absent') ON DUPLICATE KEY UPDATE status='Absent'";
                    $insStmt = $conn->prepare($ins);
                    $insStmt->bindParam(':emp_id', $emp['id']);
                    $insStmt->bindParam(':date', $date);
                    $insStmt->execute();

                    $deductionModel->applyAutomatedDeduction($emp['id'], 'Full Day Absence', $date, 'Unauthorized Absence on ' . $date, 'Attendance System');
                }
                $output[] = "Marked absent and created deductions for " . count($absent_employees) . " employees.";
            }

            // 3. Deduction for Unpaid Leaves
            $unpaidQuery = "SELECT lr.employee_id, e.basic_salary FROM leave_requests lr
                            JOIN leave_types lt ON lr.leave_type_id = lt.id
                            JOIN employees e ON lr.employee_id = e.id
                            WHERE :date BETWEEN lr.start_date AND lr.end_date AND lr.status = 'Approved' AND lt.is_paid = 0";
            $unpaidStmt = $conn->prepare($unpaidQuery);
            $unpaidStmt->bindParam(':date', $date);
            $unpaidStmt->execute();
            $unpaid_leaves = $unpaidStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($unpaid_leaves as $ul) {
                $ins = "INSERT INTO attendance (employee_id, date, status) VALUES (:emp_id, :date, 'Unpaid Leave') ON DUPLICATE KEY UPDATE status='Unpaid Leave'";
                $insStmt = $conn->prepare($ins);
                $insStmt->bindParam(':emp_id', $ul['employee_id']);
                $insStmt->bindParam(':date', $date);
                $insStmt->execute();
            }
            $output[] = "Processed unpaid leaves.";

            // 4. Log Paid Leaves in attendance
            $paidQuery = "SELECT lr.employee_id FROM leave_requests lr
                            JOIN leave_types lt ON lr.leave_type_id = lt.id
                            WHERE :date BETWEEN lr.start_date AND lr.end_date AND lr.status = 'Approved' AND lt.is_paid = 1";
            $paidStmt = $conn->prepare($paidQuery);
            $paidStmt->bindParam(':date', $date);
            $paidStmt->execute();
            $paid_leaves = $paidStmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($paid_leaves as $pl) {
                 $chkStmt = $conn->prepare("SELECT id FROM deductions WHERE employee_id = ? AND date = ? AND type = 'Unpaid Leave' AND status = 'Active'");
                 $chkStmt->execute([$pl['employee_id'], $date]);
                 $is_excess = $chkStmt->rowCount() > 0;
                 $final_status = $is_excess ? 'Unpaid Leave' : 'Paid Leave';

                 $ins = "INSERT INTO attendance (employee_id, date, status) VALUES (:emp_id, :date, :status) ON DUPLICATE KEY UPDATE status=:status";
                 $insStmt = $conn->prepare($ins);
                 $insStmt->bindParam(':emp_id', $pl['employee_id']);
                 $insStmt->bindParam(':date', $date);
                 $insStmt->bindParam(':status', $final_status);
                 $insStmt->execute();
            }
            
            $final_last_run = $date;
        }

        // Update the last run file
        if ($final_last_run > (file_exists($last_run_file) ? trim(file_get_contents($last_run_file)) : '')) {
            file_put_contents($last_run_file, $final_last_run);
        }

        echo implode("<br>", $output);
    }
}
