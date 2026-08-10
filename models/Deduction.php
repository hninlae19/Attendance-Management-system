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
    public function applyAutomatedDeduction($employee_id, $type, $date, $reason, $source, $related_id = null, $deduction_days = null, $status = 'Active') {
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
            $updQuery = "UPDATE " . $this->table . " SET amount = :amount, status = 'Active', reason = :reason, source = :source, related_id = :related_id, deduction_days_hours = :deduction_days WHERE id = :id";
            $updStmt = $this->conn->prepare($updQuery);
            $updStmt->execute([
                ':amount' => $amount,
                ':reason' => $reason,
                ':source' => $source,
                ':related_id' => $related_id,
                ':deduction_days' => $deduction_days,
                ':id' => $existing['id']
            ]);
        } else {
            // Insert
            $insQuery = "INSERT INTO " . $this->table . " (employee_id, amount, reason, date, type, status, source, related_id, deduction_days_hours) 
                         VALUES (:emp_id, :amount, :reason, :date, :type, :status, :source, :related_id, :deduction_days)";
            $insStmt = $this->conn->prepare($insQuery);
            $insStmt->execute([
                ':emp_id' => $employee_id,
                ':amount' => $amount,
                ':reason' => $reason,
                ':date' => $date,
                ':type' => $type,
                ':status' => $status,
                ':source' => $source,
                ':related_id' => $related_id,
                ':deduction_days' => $deduction_days
            ]);
        }
        return true;
    }

    /**
     * Cancel an automated deduction (e.g. if attendance corrected)
     */
    public function cancelAutomatedDeduction($employee_id, $type, $date, $related_id = null) {
        $query = "UPDATE " . $this->table . " SET status = 'Cancelled' WHERE employee_id = :emp_id AND date = :date AND type = :type AND status = 'Active'";
        $params = [
            ':emp_id' => $employee_id,
            ':date' => $date,
            ':type' => $type
        ];
        
        if ($related_id !== null) {
            $query .= " AND related_id = :related_id";
            $params[':related_id'] = $related_id;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return true;
    }

    /**
     * Applies an automated deduction for a date range
     */
    public function applyAutomatedRangeDeduction($employee_id, $type, $start_date, $end_date, $total_days, $reason, $source, $related_id = null, $status = 'Active') {
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
        
        if (isset($settings['auto_deduction_enabled']) && $settings['auto_deduction_enabled'] == 0) {
            return false;
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
            $amount = $daily_rate * $rate_multiplier * $total_days;
        } else {
            // Fixed amount per day
            $amount = $rate_multiplier * $total_days;
        }

        if ($amount <= 0) return true;

        // Ensure we don't insert duplicates for the same leave request (related_id)
        if ($related_id !== null) {
            $checkQuery = "SELECT id, status FROM " . $this->table . " WHERE employee_id = :emp_id AND related_id = :related_id AND type = :type";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([':emp_id' => $employee_id, ':related_id' => $related_id, ':type' => $type]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $updQuery = "UPDATE " . $this->table . " SET amount = :amount, status = :status, reason = :reason, source = :source, start_date = :start_date, end_date = :end_date, total_absent_days = :total_days WHERE id = :id";
                $updStmt = $this->conn->prepare($updQuery);
                $updStmt->execute([
                    ':amount' => $amount,
                    ':status' => $status,
                    ':reason' => $reason,
                    ':source' => $source,
                    ':start_date' => $start_date,
                    ':end_date' => $end_date,
                    ':total_days' => $total_days,
                    ':id' => $existing['id']
                ]);
                return true;
            }
        }

        $insQuery = "INSERT INTO " . $this->table . " (employee_id, amount, reason, date, type, status, source, related_id, start_date, end_date, total_absent_days) 
                     VALUES (:emp_id, :amount, :reason, :date, :type, :status, :source, :related_id, :start_date, :end_date, :total_days)";
        $insStmt = $this->conn->prepare($insQuery);
        $insStmt->execute([
            ':emp_id' => $employee_id,
            ':amount' => $amount,
            ':reason' => $reason,
            ':date' => $start_date, // use start_date as the main record date
            ':type' => $type,
            ':status' => $status,
            ':source' => $source,
            ':related_id' => $related_id,
            ':start_date' => $start_date,
            ':end_date' => $end_date,
            ':total_days' => $total_days
        ]);

        return true;
    }

    public function getTotalCount($filters = []) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " d
                  LEFT JOIN employees e ON d.employee_id = e.id
                  WHERE d.type != 'Late'";
        
        $params = [];
        if (!empty($filters['search'])) {
            $query .= " AND (e.first_name LIKE :search OR e.last_name LIKE :search OR e.employee_code LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['type'])) {
            $query .= " AND d.type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['date_start'])) {
            $query .= " AND d.date >= :date_start";
            $params[':date_start'] = $filters['date_start'];
        }
        if (!empty($filters['date_end'])) {
            $query .= " AND d.date <= :date_end";
            $params[':date_end'] = $filters['date_end'];
        }
        if (!empty($filters['min_absent_days'])) {
            $query .= " AND d.total_absent_days >= :min_absent_days";
            $params[':min_absent_days'] = $filters['min_absent_days'];
        }
        if (!empty($filters['max_absent_days'])) {
            $query .= " AND d.total_absent_days <= :max_absent_days";
            $params[':max_absent_days'] = $filters['max_absent_days'];
        }

        $stmt = $this->conn->prepare($query);
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    }

    public function getFilteredDeductions($filters = [], $sort = 'date', $dir = 'DESC', $limit = 10, $offset = 0) {
        $query = "SELECT d.*, e.first_name, e.last_name, e.employee_code FROM " . $this->table . " d
                  LEFT JOIN employees e ON d.employee_id = e.id
                  WHERE d.type != 'Late'";
        
        $params = [];
        if (!empty($filters['search'])) {
            $query .= " AND (e.first_name LIKE :search OR e.last_name LIKE :search OR e.employee_code LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['type'])) {
            $query .= " AND d.type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['date_start'])) {
            $query .= " AND d.date >= :date_start";
            $params[':date_start'] = $filters['date_start'];
        }
        if (!empty($filters['date_end'])) {
            $query .= " AND d.date <= :date_end";
            $params[':date_end'] = $filters['date_end'];
        }
        if (!empty($filters['min_absent_days'])) {
            $query .= " AND d.total_absent_days >= :min_absent_days";
            $params[':min_absent_days'] = $filters['min_absent_days'];
        }
        if (!empty($filters['max_absent_days'])) {
            $query .= " AND d.total_absent_days <= :max_absent_days";
            $params[':max_absent_days'] = $filters['max_absent_days'];
        }

        $allowed_sorts = ['date', 'start_date', 'end_date', 'total_absent_days', 'amount'];
        if (!in_array($sort, $allowed_sorts)) {
            $sort = 'date';
        }
        $dir = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';

        $query .= " ORDER BY d." . $sort . " " . $dir . " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
