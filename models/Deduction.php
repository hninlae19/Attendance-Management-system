<?php
require_once __DIR__ . '/Setting.php';

class Deduction {
    private $conn;
    private $table = 'deductions';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Applies an automated deduction according to settings
     * 
     * @param int $employee_id
     * @param string $type (e.g., 'Half Day Absence', 'Full Day Absence', 'Unpaid Leave', 'Late')
     * @param string $date
     * @param string $reason
     * @param string $source
     */
    public function applyAutomatedDeduction($employee_id, $type, $date, $reason, $source) {
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
        
        if (isset($settings['auto_deduction_enabled']) && $settings['auto_deduction_enabled'] == 0) {
            return false; // Auto deduction disabled
        }

        // Check for existing deduction to prevent duplicates
        $checkQuery = "SELECT id, status FROM " . $this->table . " WHERE employee_id = :emp_id AND date = :date AND type = :type";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->execute([':emp_id' => $employee_id, ':date' => $date, ':type' => $type]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        // If it exists and is not cancelled, skip or update? 
        // The rule says "prevent duplicate deduction records". If it's Active, we just update it or ignore. Let's ignore if Active/Applied to prevent duplicates.
        if ($existing && $existing['status'] !== 'Cancelled') {
            return true; 
        }

        // Calculate amount
        $empStmt = $this->conn->prepare("SELECT basic_salary FROM employees WHERE id = :emp_id");
        $empStmt->execute([':emp_id' => $employee_id]);
        $emp = $empStmt->fetch(PDO::FETCH_ASSOC);
        if (!$emp) return false;

        $basic_salary = $emp['basic_salary'];
        $daily_rate = $basic_salary / 30; // standard daily rate

        $rate_multiplier = 0;
        if ($type === 'Half Day Absence') {
            $rate_multiplier = $settings['half_day_deduction_rate'] ?? 0.5;
        } elseif ($type === 'Full Day Absence') {
            $rate_multiplier = $settings['absent_deduction_rate'] ?? 1.0;
        } elseif ($type === 'Unpaid Leave') {
            $rate_multiplier = $settings['unpaid_leave_deduction_rate'] ?? 1.0;
        } elseif ($type === 'Late') {
            $rate_multiplier = $settings['late_deduction_rate'] ?? 0;
        }

        $method = $settings['deduction_calculation_method'] ?? 'Salary-Based';
        $amount = 0;

        if ($method === 'Salary-Based') {
            $amount = $daily_rate * $rate_multiplier;
        } else {
            // If Fixed Amount, we assume the rate is actually the fixed amount value
            $amount = $rate_multiplier;
        }

        if ($amount <= 0) return true; // Nothing to deduct

        if ($existing && $existing['status'] === 'Cancelled') {
            // Reactivate
            $updQuery = "UPDATE " . $this->table . " SET amount = :amount, status = 'Active', reason = :reason, source = :source WHERE id = :id";
            $updStmt = $this->conn->prepare($updQuery);
            $updStmt->execute([
                ':amount' => $amount,
                ':reason' => $reason,
                ':source' => $source,
                ':id' => $existing['id']
            ]);
        } else {
            // Insert
            $insQuery = "INSERT INTO " . $this->table . " (employee_id, amount, reason, date, type, status, source) 
                         VALUES (:emp_id, :amount, :reason, :date, :type, 'Active', :source)";
            $insStmt = $this->conn->prepare($insQuery);
            $insStmt->execute([
                ':emp_id' => $employee_id,
                ':amount' => $amount,
                ':reason' => $reason,
                ':date' => $date,
                ':type' => $type,
                ':source' => $source
            ]);
        }
        return true;
    }

    /**
     * Cancel an automated deduction (e.g. if attendance corrected)
     */
    public function cancelAutomatedDeduction($employee_id, $type, $date) {
        $query = "UPDATE " . $this->table . " SET status = 'Cancelled' WHERE employee_id = :emp_id AND date = :date AND type = :type AND status = 'Active'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':emp_id' => $employee_id,
            ':date' => $date,
            ':type' => $type
        ]);
        return true;
    }
}
