<?php
class LeaveRequest {
    private $conn;
    private $table = 'leave_requests';

    public $id;
    public $employee_id;
    public $leave_type_id;
    public $start_date;
    public $end_date;
    public $days;
    public $reason;
    public $status;
    public $admin_remark;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT lr.*, e.first_name, e.last_name, e.employee_code, lt.name as leave_type_name, lt.is_paid 
                  FROM " . $this->table . " lr
                  LEFT JOIN employees e ON lr.employee_id = e.id
                  LEFT JOIN leave_types lt ON lr.leave_type_id = lt.id
                  ORDER BY lr.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handleRequest($id, $action, $remark = '') {
        $status = ($action === 'approve') ? 'Approved' : 'Rejected';
        
        $query = "UPDATE " . $this->table . " SET status = :status, admin_remark = :remark WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':remark', $remark);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            if ($action === 'approve') {
                // Fetch leave details
                $leaveStmt = $this->conn->prepare("SELECT lr.*, lt.is_paid FROM " . $this->table . " lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.id = ?");
                $leaveStmt->execute([$id]);
                $leave = $leaveStmt->fetch(PDO::FETCH_ASSOC);

                if ($leave && $leave['is_paid'] == 1) {
                    $year = date('Y', strtotime($leave['start_date']));
                    // Get Settings
                    $setStmt = $this->conn->query("SELECT paid_leave_limit FROM settings LIMIT 1");
                    $settings = $setStmt->fetch(PDO::FETCH_ASSOC);
                    $limit = $settings['paid_leave_limit'] ?? 35;

                    // Get total approved paid leaves for this year (excluding the current one since it was just approved? Wait, if it was just approved, the sum WILL include it. So we must calculate the excess.
                    // Wait, if it includes it, total = sum. If total > limit, excess = total - limit.
                    // But we only want to deduct for THIS request's excess, not past ones if they were already deducted.
                    // So we get total BEFORE this request.
                    $sumStmt = $this->conn->prepare("SELECT SUM(lr.days) as total FROM " . $this->table . " lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.employee_id = ? AND lr.status = 'Approved' AND lt.is_paid = 1 AND YEAR(lr.start_date) = ? AND lr.id != ?");
                    $sumStmt->execute([$leave['employee_id'], $year, $id]);
                    $past_total = $sumStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

                    $new_total = $past_total + $leave['days'];
                    if ($new_total > $limit) {
                        $excess_days = $new_total - $limit;
                        if ($excess_days > $leave['days']) {
                            $excess_days = $leave['days'];
                        }

                        if ($excess_days > 0) {
                            // Get employee salary to calculate deduction
                            $empStmt = $this->conn->prepare("SELECT basic_salary FROM employees WHERE id = ?");
                            $empStmt->execute([$leave['employee_id']]);
                            $emp = $empStmt->fetch(PDO::FETCH_ASSOC);
                            
                            $daily_rate = ($emp['basic_salary'] ?? 0) / 30;
                            $deduction_amount = $excess_days * $daily_rate;

                            // Insert into deductions table
                            $deductStmt = $this->conn->prepare("INSERT INTO deductions (employee_id, amount, type, reason, date, created_by, status) VALUES (?, ?, 'Automated Excess Leave', 'Exceeded Paid Leave Limit by {$excess_days} days', ?, 'System', 'Applied')");
                            $deductStmt->execute([$leave['employee_id'], $deduction_amount, $leave['start_date']]);
                        }
                    }
                }
            }

            // Get employee ID for notification
            $q2 = "SELECT employee_id FROM " . $this->table . " WHERE id = :id";
            $s2 = $this->conn->prepare($q2);
            $s2->bindParam(':id', $id);
            $s2->execute();
            $row = $s2->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                require_once __DIR__ . '/Notification.php';
                $notif = new Notification();
                $notif->create($row['employee_id'], "Your leave request has been {$status}.", 'leave', '/employee/leaves');
            }
            return true;
        }
        return false;
    }
}
