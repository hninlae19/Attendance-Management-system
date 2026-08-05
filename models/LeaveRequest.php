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

                require_once __DIR__ . '/Deduction.php';
                $deduction = new Deduction();

                if ($leave && $leave['is_paid'] == 1) {
                    $year = date('Y', strtotime($leave['start_date']));
                    
                    // Get limit from leave_types
                    $limit = $leave['days_allowed'] ?? 0;

                    // Get total approved paid leaves of this type for this year
                    $sumStmt = $this->conn->prepare("SELECT SUM(lr.days) as total FROM " . $this->table . " lr WHERE lr.employee_id = ? AND lr.status = 'Approved' AND lr.leave_type_id = ? AND YEAR(lr.start_date) = ? AND lr.id != ?");
                    $sumStmt->execute([$leave['employee_id'], $leave['leave_type_id'], $year, $id]);
                    $past_total = $sumStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

                    $new_total = $past_total + $leave['days'];
                    if ($new_total > $limit) {
                        $excess_days = $new_total - $limit;
                        if ($excess_days > $leave['days']) {
                            $excess_days = $leave['days'];
                        }

                        if ($excess_days > 0) {
                            $current_date = strtotime($leave['end_date']);
                            for ($i = 0; $i < $excess_days; $i++) {
                                $date_str = date('Y-m-d', $current_date);
                                $deduction->applyAutomatedDeduction($leave['employee_id'], 'Unpaid Leave', $date_str, 'Exceeded Paid Leave Limit (Unpaid Leave)', 'Leave Management System');
                                $current_date = strtotime("-1 day", $current_date);
                            }
                        }
                    }
                } elseif ($leave && $leave['is_paid'] == 0) {
                    // Unpaid leave creates deductions for all days
                    $current_date = strtotime($leave['start_date']);
                    $end_date = strtotime($leave['end_date']);
                    while ($current_date <= $end_date) {
                        $date_str = date('Y-m-d', $current_date);
                        $deduction->applyAutomatedDeduction($leave['employee_id'], 'Unpaid Leave', $date_str, 'Approved Unpaid Leave', 'Leave Management System');
                        $current_date = strtotime("+1 day", $current_date);
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
