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
        $date = date('Y-m-d');
        
        $db = new Database();
        $conn = $db->getConnection();

        $output = [];

        // 1. Auto Check-Out
        $attendanceModel->autoCheckoutIfMissed();
        $output[] = "Auto check-out process executed.";

        // 1.5 Process Late and Half Day Deductions for today's attendances
        $attQuery = "SELECT id, employee_id, status FROM attendance WHERE date = :date AND (status = 'Late' OR status = 'Half Day')";
        $attStmt = $conn->prepare($attQuery);
        $attStmt->bindParam(':date', $date);
        $attStmt->execute();
        $late_half_attendances = $attStmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../models/Deduction.php';
        $deductionModel = new Deduction();

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

        // 2. Mark Absent for working days (Run at end of day, e.g., 23:59 or whenever this runs after auto-checkout)
        $holidayModel = $this->model('Holiday');
        if (!$holidayModel->isHoliday($date) && date('N', strtotime($date)) < 6) {
            // Find employees with no attendance and no approved leave
            $empQuery = "SELECT id, basic_salary FROM employees WHERE status = 'Active' AND id NOT IN (SELECT employee_id FROM attendance WHERE date = :date) AND id NOT IN (SELECT employee_id FROM leave_requests WHERE :date BETWEEN start_date AND end_date AND status = 'Approved')";
            $empStmt = $conn->prepare($empQuery);
            $empStmt->bindParam(':date', $date);
            $empStmt->execute();
            $absent_employees = $empStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($absent_employees as $emp) {
                // Mark Absent
                $ins = "INSERT INTO attendance (employee_id, date, status) VALUES (:emp_id, :date, 'Absent')";
                $insStmt = $conn->prepare($ins);
                $insStmt->bindParam(':emp_id', $emp['id']);
                $insStmt->bindParam(':date', $date);
                $insStmt->execute();

                require_once __DIR__ . '/../models/Deduction.php';
                $deduction = new Deduction();
                $deduction->applyAutomatedDeduction($emp['id'], 'Full Day Absence', $date, 'Unauthorized Absence on ' . $date, 'Attendance System');
            }
            $output[] = "Marked absent and created deductions for " . count($absent_employees) . " employees.";
        }

        // 3. Deduction for Unpaid Leaves today
        $unpaidQuery = "SELECT lr.employee_id, e.basic_salary FROM leave_requests lr
                        JOIN leave_types lt ON lr.leave_type_id = lt.id
                        JOIN employees e ON lr.employee_id = e.id
                        WHERE :date BETWEEN lr.start_date AND lr.end_date AND lr.status = 'Approved' AND lt.is_paid = 0";
        $unpaidStmt = $conn->prepare($unpaidQuery);
        $unpaidStmt->bindParam(':date', $date);
        $unpaidStmt->execute();
        $unpaid_leaves = $unpaidStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($unpaid_leaves as $ul) {
            // The deduction for the entire unpaid leave period is already generated when the leave is requested/approved.
            // Therefore, we do not need to create daily deduction records here.

            // Also log as 'Unpaid Leave' in attendance
            $ins = "INSERT INTO attendance (employee_id, date, status) VALUES (:emp_id, :date, 'Unpaid Leave') ON DUPLICATE KEY UPDATE status='Unpaid Leave'";
            $insStmt = $conn->prepare($ins);
            $insStmt->bindParam(':emp_id', $ul['employee_id']);
            $insStmt->bindParam(':date', $date);
            $insStmt->execute();
        }
        $output[] = "Processed deductions for unpaid leaves.";

        // 4. Log Paid Leaves in attendance
        $paidQuery = "SELECT lr.employee_id FROM leave_requests lr
                        JOIN leave_types lt ON lr.leave_type_id = lt.id
                        WHERE :date BETWEEN lr.start_date AND lr.end_date AND lr.status = 'Approved' AND lt.is_paid = 1";
        $paidStmt = $conn->prepare($paidQuery);
        $paidStmt->bindParam(':date', $date);
        $paidStmt->execute();
        $paid_leaves = $paidStmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($paid_leaves as $pl) {
             // Check if this specific day was converted to unpaid due to limit excess
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

        echo implode("<br>", $output);
    }
}
