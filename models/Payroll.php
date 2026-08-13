<?php
require_once __DIR__ . '/Setting.php';

class Payroll {
    private $conn;
    private $table = 'payroll';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($month = null, $year = null) {
        $month = $month ?? date('n');
        $year = $year ?? date('Y');

        $query = "SELECT p.*, e.first_name, e.last_name, e.employee_code, d.name as department_name 
                  FROM " . $this->table . " p
                  LEFT JOIN employees e ON p.employee_id = e.id
                  LEFT JOIN departments d ON e.department_id = d.id
                  WHERE p.month = :month AND p.year = :year
                  ORDER BY e.first_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByEmployee($employee_id) {
        $query = "SELECT p.*, e.first_name, e.last_name, e.employee_code, d.name as department_name 
                  FROM " . $this->table . " p
                  LEFT JOIN employees e ON p.employee_id = e.id
                  LEFT JOIN departments d ON e.department_id = d.id
                  WHERE p.employee_id = :employee_id
                  ORDER BY p.year DESC, p.month DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generatePayroll($month, $year) {
        // 1. Get Settings
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();

        // 2. Get All active employees
        $empQuery = "SELECT e.* FROM employees e 
                     INNER JOIN users u ON e.user_id = u.id 
                     WHERE u.status = 'Active'";
        $empStmt = $this->conn->prepare($empQuery);
        $empStmt->execute();
        $employees = $empStmt->fetchAll(PDO::FETCH_ASSOC);

        $successCount = 0;

        foreach ($employees as $emp) {
            // Check if payroll already generated
            $checkQuery = "SELECT id, status FROM payroll WHERE employee_id = :emp_id AND month = :month AND year = :year";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':emp_id', $emp['id']);
            $checkStmt->bindParam(':month', $month);
            $checkStmt->bindParam(':year', $year);
            $checkStmt->execute();
            
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                if ($existing['status'] === 'Paid') {
                    continue; // Skip if already paid
                } else {
                    // Reset applied deductions back to active so they can be picked up again
                    $resetDed = $this->conn->prepare("UPDATE deductions SET status = 'Active', payroll_id = NULL WHERE payroll_id = :id");
                    $resetDed->execute([':id' => $existing['id']]);
                    
                    // Delete pending payroll to regenerate
                    $delStmt = $this->conn->prepare("DELETE FROM payroll WHERE id = :id");
                    $delStmt->execute([':id' => $existing['id']]);
                }
            }

            // Daily rate based on actual days in the month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $dailyRate = $emp['basic_salary'] / $daysInMonth;
            $hourlyRate = $dailyRate / $settings['working_hours'];
            
            require_once __DIR__ . '/Holiday.php';
            $holidayModel = new Holiday();
            
            $basicSalaryForMonth = $emp['basic_salary'];
            
            if (!empty($emp['join_date'])) {
                $joinTime = strtotime($emp['join_date']);
                $joinYear = date('Y', $joinTime);
                $joinMonth = date('n', $joinTime);
                $joinDay = date('j', $joinTime);
                
                // If joined after this month, skip generating payroll
                if ($joinTime > strtotime(sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth))) {
                    continue;
                }
                
                // If joined in the current payroll month, apply pro-rated salary
                if ($joinYear == $year && $joinMonth == $month) {
                    $totalWorkingDaysInMonth = 0;
                    $actualEligibleWorkingDays = 0;
                    
                    for ($d = 1; $d <= $daysInMonth; $d++) {
                        $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $d);
                        $isWeekend = date('N', strtotime($currentDate)) >= 6;
                        $isHoliday = $holidayModel->isHoliday($currentDate);
                        
                        if (!$isWeekend && !$isHoliday) {
                            $totalWorkingDaysInMonth++;
                            if ($d >= $joinDay) {
                                $actualEligibleWorkingDays++;
                            }
                        }
                    }
                    
                    if ($totalWorkingDaysInMonth > 0) {
                        $basicSalaryForMonth = ($emp['basic_salary'] / $totalWorkingDaysInMonth) * $actualEligibleWorkingDays;
                    }
                }
            }

            // Calculate Attendance Data
            $attQuery = "SELECT status, COUNT(id) as count FROM attendance 
                         WHERE employee_id = :emp_id AND MONTH(date) = :month AND YEAR(date) = :year 
                         GROUP BY status";
            $attStmt = $this->conn->prepare($attQuery);
            $attStmt->bindParam(':emp_id', $emp['id']);
            $attStmt->bindParam(':month', $month);
            $attStmt->bindParam(':year', $year);
            $attStmt->execute();
            $attStats = ['Present' => 0, 'Late' => 0, 'Half Day' => 0, 'Absent' => 0];
            while($row = $attStmt->fetch(PDO::FETCH_ASSOC)) {
                $attStats[$row['status']] = $row['count'];
            }

            // Automated deductions are now handled solely by the daily Cron job.
            // We just aggregate from the deductions table below.

            // Calculate OT
            $otQuery = "SELECT type, SUM(hours) as total_hours FROM overtime_requests 
                        WHERE employee_id = :emp_id AND status = 'Approved' 
                        AND MONTH(date) = :month AND YEAR(date) = :year GROUP BY type";
            $otStmt = $this->conn->prepare($otQuery);
            $otStmt->bindParam(':emp_id', $emp['id']);
            $otStmt->bindParam(':month', $month);
            $otStmt->bindParam(':year', $year);
            $otStmt->execute();
            
            $otAmount = 0;
            $totalOtHours = 0;
            while($row = $otStmt->fetch(PDO::FETCH_ASSOC)) {
                $rate = 0;
                if($row['type'] === 'Working Day') $rate = $settings['weekday_ot_rate'] ?? 1.5;
                if($row['type'] === 'Weekend') $rate = $settings['weekend_ot_rate'] ?? 2.0;
                if($row['type'] === 'Holiday') $rate = $settings['holiday_ot_rate'] ?? 3.0;
                
                $totalOtHours += $row['total_hours'];
                $otAmount += ($hourlyRate * $rate * $row['total_hours']); // OT is usually hourly rate * multiplier
            }
            
            // Calculate OT from Assignments
            $otaQuery = "SELECT a.date, a.start_time, a.end_time 
                         FROM overtime_assignments a 
                         JOIN overtime_assignment_employees oae ON a.id = oae.assignment_id 
                         WHERE oae.employee_id = :emp_id AND oae.status IN ('Assigned', 'Completed') 
                         AND a.status = 'Active' 
                         AND MONTH(a.date) = :month AND YEAR(a.date) = :year";
            $otaStmt = $this->conn->prepare($otaQuery);
            $otaStmt->execute([':emp_id' => $emp['id'], ':month' => $month, ':year' => $year]);
            
            while($otaRow = $otaStmt->fetch(PDO::FETCH_ASSOC)) {
                $start = strtotime($otaRow['start_time']);
                $end = strtotime($otaRow['end_time']);
                if ($end < $start) {
                    $end += 86400; // Add 24 hours if shift spans midnight
                }
                $hours = round(($end - $start) / 3600, 2);
                
                $rate = $settings['weekday_ot_rate'] ?? 1.5;
                if ($holidayModel->isHoliday($otaRow['date'])) {
                    $rate = $settings['holiday_ot_rate'] ?? 3.0;
                } elseif (date('N', strtotime($otaRow['date'])) >= 6) {
                    $rate = $settings['weekend_ot_rate'] ?? 2.0;
                }
                
                $totalOtHours += $hours;
                $otAmount += ($hourlyRate * $rate * $hours);
            }

            // Calculate Deductions directly from the deductions table
            $leaveDeduction = 0;
            $lateDeduction = 0;
            $otherDeduction = 0;
            
            require_once __DIR__ . '/Deduction.php';
            $deductionModel = new Deduction();
            $deductionModel->recalculateActiveDeductions($emp['id'], $emp['basic_salary']);
            
            // Fetch from deductions table
            $dedStmt = $this->conn->prepare("SELECT id, type, reason, amount FROM deductions WHERE employee_id = ? AND MONTH(date) = ? AND YEAR(date) = ? AND status = 'Active'");
            $dedStmt->execute([$emp['id'], $month, $year]);
            
            $active_deduction_ids = [];
            
            while($row = $dedStmt->fetch(PDO::FETCH_ASSOC)) {
                $active_deduction_ids[] = $row['id'];
                if (in_array($row['type'], ['Half Day Absence', 'Full Day Absence', 'Unpaid Leave'])) {
                    $leaveDeduction += $row['amount'];
                } elseif ($row['type'] === 'Late') {
                    $lateDeduction += $row['amount'];
                } else {
                    $otherDeduction += $row['amount'];
                }
            }
            $totalDeductions = $leaveDeduction + $lateDeduction + $otherDeduction;

            // Calculate Bonuses from bonuses table
            $bonusStmt = $this->conn->prepare("SELECT SUM(amount) as total FROM bonuses WHERE employee_id = ? AND MONTH(date) = ? AND YEAR(date) = ?");
            $bonusStmt->execute([$emp['id'], $month, $year]);
            $bonusAmount = $bonusStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $allowanceAmount = 0; // Fixed allowances if any

            $grossSalary = $basicSalaryForMonth + $otAmount + $bonusAmount + $allowanceAmount;
            $netSalary = $grossSalary - $leaveDeduction - $lateDeduction - $otherDeduction;
            if ($netSalary < 0) $netSalary = 0;

            // Calculate total leave days for payroll record
            $leaveQuery = "SELECT SUM(days) as total FROM leave_requests WHERE employee_id = :emp_id AND status = 'Approved' AND MONTH(start_date) = :month AND YEAR(start_date) = :year";
            $leaveStmt = $this->conn->prepare($leaveQuery);
            $leaveStmt->bindParam(':emp_id', $emp['id']);
            $leaveStmt->bindParam(':month', $month);
            $leaveStmt->bindParam(':year', $year);
            $leaveStmt->execute();
            $totalLeaveDays = $leaveStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Insert Payroll
            $insertQuery = "INSERT INTO payroll SET 
                employee_id = :emp_id, month = :month, year = :year, basic_salary = :basic_salary,
                present_days = :present, half_days = :half, absent_days = :absent, late_days = :late, leave_days = :leave,
                ot_hours = :ot_hours, ot_amount = :ot, bonus_amount = :bonus, allowance_amount = :allowance, 
                leave_deduction_amount = :leave_deduction, late_deduction_amount = :late_deduction, other_deduction_amount = :other_deduction,
                deduction_amount = :deduction,
                gross_salary = :gross, net_salary = :net, status = 'Pending'";
            
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->bindParam(':emp_id', $emp['id']);
            $insertStmt->bindParam(':month', $month);
            $insertStmt->bindParam(':year', $year);
            $insertStmt->bindParam(':basic_salary', $basicSalaryForMonth);
            $insertStmt->bindParam(':present', $attStats['Present']);
            $insertStmt->bindParam(':half', $attStats['Half Day']);
            $insertStmt->bindParam(':absent', $attStats['Absent']);
            $insertStmt->bindParam(':late', $attStats['Late']);
            $insertStmt->bindParam(':leave', $totalLeaveDays);
            $insertStmt->bindParam(':ot_hours', $totalOtHours);
            $insertStmt->bindParam(':ot', $otAmount);
            $insertStmt->bindParam(':bonus', $bonusAmount);
            $insertStmt->bindParam(':allowance', $allowanceAmount);
            $insertStmt->bindParam(':leave_deduction', $leaveDeduction);
            $insertStmt->bindParam(':late_deduction', $lateDeduction);
            $insertStmt->bindParam(':other_deduction', $otherDeduction);
            $insertStmt->bindParam(':deduction', $totalDeductions);
            $insertStmt->bindParam(':gross', $grossSalary);
            $insertStmt->bindParam(':net', $netSalary);
            
            if($insertStmt->execute()) {
                $payroll_id = $this->conn->lastInsertId();
                if (!empty($active_deduction_ids)) {
                    $placeholders = implode(',', array_fill(0, count($active_deduction_ids), '?'));
                    $updDed = $this->conn->prepare("UPDATE deductions SET status = 'Applied', payroll_id = ? WHERE id IN ($placeholders)");
                    $params = array_merge([$payroll_id], $active_deduction_ids);
                    $updDed->execute($params);
                }

                $successCount++;
                require_once __DIR__ . '/Notification.php';
                $notif = new Notification();
                $monthName = date('F', mktime(0, 0, 0, $month, 10));
                $notif->create($emp['user_id'], "Your payroll for {$monthName} {$year} has been generated.", 'payroll', '/employee/payslips', 'Payroll Generated');
            }
        }
        return $successCount;
    }

    public function markAsPaid($id, $paymentMethod) {
        $query = "UPDATE " . $this->table . " SET status = 'Paid', payment_method = :pm, payment_date = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pm', $paymentMethod);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
