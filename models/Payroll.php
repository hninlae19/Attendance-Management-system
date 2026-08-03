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
            $checkQuery = "SELECT id FROM payroll WHERE employee_id = :emp_id AND month = :month AND year = :year";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':emp_id', $emp['id']);
            $checkStmt->bindParam(':month', $month);
            $checkStmt->bindParam(':year', $year);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) continue; // Skip if already generated

            // Daily rate based on roughly 30 days or actual working days
            // For simplicity, let's use Basic Salary / 30
            $dailyRate = $emp['basic_salary'] / 30;
            $hourlyRate = $dailyRate / $settings['working_hours'];

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

            // Calculate automated deductions for attendance (Absent, Half Day, Late)
            $autoCheck = $this->conn->prepare("SELECT id FROM deductions WHERE employee_id = ? AND type LIKE 'Automated%' AND MONTH(date) = ? AND YEAR(date) = ?");
            $autoCheck->execute([$emp['id'], $month, $year]);
            if ($autoCheck->rowCount() == 0) {
                // If not processed, process now. Date defaults to end of month.
                $dDate = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
                
                if ($attStats['Absent'] > 0) {
                    $absentDeduction = $attStats['Absent'] * $dailyRate;
                    $this->conn->prepare("INSERT INTO deductions (employee_id, amount, type, reason, date, created_by, status) VALUES (?, ?, 'Automated Absence', 'Unexcused absence', ?, 'System', 'Applied')")
                         ->execute([$emp['id'], $absentDeduction, $dDate]);
                }
                if ($attStats['Half Day'] > 0) {
                    $halfDayDeduction = $attStats['Half Day'] * ($dailyRate / 2);
                    $this->conn->prepare("INSERT INTO deductions (employee_id, amount, type, reason, date, created_by, status) VALUES (?, ?, 'Automated Half-Day', 'Worked less than required hours', ?, 'System', 'Applied')")
                         ->execute([$emp['id'], $halfDayDeduction, $dDate]);
                }
                if ($attStats['Late'] > 0) {
                    $lateDeduction = $attStats['Late'] * ($hourlyRate * 0.5);
                    $this->conn->prepare("INSERT INTO deductions (employee_id, amount, type, reason, date, created_by, status) VALUES (?, ?, 'Automated Late', 'Late check-ins', ?, 'System', 'Applied')")
                         ->execute([$emp['id'], $lateDeduction, $dDate]);
                }
            }

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
            while($row = $otStmt->fetch(PDO::FETCH_ASSOC)) {
                $rate = 0;
                if($row['type'] === 'Working Day') $rate = $settings['weekday_ot_rate'] ?? 1.5;
                if($row['type'] === 'Weekend') $rate = $settings['weekend_ot_rate'] ?? 2.0;
                if($row['type'] === 'Holiday') $rate = $settings['holiday_ot_rate'] ?? 3.0;
                
                $otAmount += ($hourlyRate * $rate * $row['total_hours']); // OT is usually hourly rate * multiplier
            }

            // Calculate Deductions from deductions table breakdown
            $dedStmt = $this->conn->prepare("SELECT type, amount FROM deductions WHERE employee_id = ? AND MONTH(date) = ? AND YEAR(date) = ? AND status != 'Cancelled'");
            $dedStmt->execute([$emp['id'], $month, $year]);
            
            $leaveDeduction = 0;
            $lateDeduction = 0;
            $otherDeduction = 0;
            
            while($row = $dedStmt->fetch(PDO::FETCH_ASSOC)) {
                if (strpos($row['type'], 'Automated Absence') !== false || strpos($row['type'], 'Automated Half-Day') !== false) {
                    $leaveDeduction += $row['amount'];
                } elseif (strpos($row['type'], 'Automated Late') !== false) {
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

            $grossSalary = $emp['basic_salary'] + $otAmount + $bonusAmount + $allowanceAmount;
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
                ot_amount = :ot, bonus_amount = :bonus, allowance_amount = :allowance, 
                deduction_amount = :deduction, leave_deduction_amount = :leave_deduction, 
                late_deduction_amount = :late_deduction, other_deduction_amount = :other_deduction,
                gross_salary = :gross, net_salary = :net, status = 'Pending'";
            
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->bindParam(':emp_id', $emp['id']);
            $insertStmt->bindParam(':month', $month);
            $insertStmt->bindParam(':year', $year);
            $insertStmt->bindParam(':basic_salary', $emp['basic_salary']);
            $insertStmt->bindParam(':present', $attStats['Present']);
            $insertStmt->bindParam(':half', $attStats['Half Day']);
            $insertStmt->bindParam(':absent', $attStats['Absent']);
            $insertStmt->bindParam(':late', $attStats['Late']);
            $insertStmt->bindParam(':leave', $totalLeaveDays);
            $insertStmt->bindParam(':ot', $otAmount);
            $insertStmt->bindParam(':bonus', $bonusAmount);
            $insertStmt->bindParam(':allowance', $allowanceAmount);
            $insertStmt->bindParam(':deduction', $totalDeductions);
            $insertStmt->bindParam(':leave_deduction', $leaveDeduction);
            $insertStmt->bindParam(':late_deduction', $lateDeduction);
            $insertStmt->bindParam(':other_deduction', $otherDeduction);
            $insertStmt->bindParam(':gross', $grossSalary);
            $insertStmt->bindParam(':net', $netSalary);
            
            if($insertStmt->execute()) {
                $successCount++;
                require_once __DIR__ . '/Notification.php';
                $notif = new Notification();
                $monthName = date('F', mktime(0, 0, 0, $month, 10));
                $notif->create($emp['id'], "Your payroll for {$monthName} {$year} has been generated.", 'payroll', '/employee/payroll');
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
