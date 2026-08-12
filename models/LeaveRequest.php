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

    public function getTotalCount($filters = []) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " lr
                  LEFT JOIN employees e ON lr.employee_id = e.id
                  WHERE 1=1";
        
        $params = [];
        if (!empty($filters['department_id'])) {
            $query .= " AND e.department_id = :dept_id";
            $params[':dept_id'] = $filters['department_id'];
        }
        if (!empty($filters['search'])) {
            $query .= " AND (e.first_name LIKE :search OR e.last_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['date'])) {
            $query .= " AND (:date BETWEEN lr.start_date AND lr.end_date)";
            $params[':date'] = $filters['date'];
        }

        $stmt = $this->conn->prepare($query);
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }

    public function getFilteredRequests($filters = [], $limit = 5, $offset = 0) {
        $query = "SELECT lr.*, e.first_name, e.last_name, e.employee_code, lt.name as leave_type_name, lt.is_paid 
                  FROM " . $this->table . " lr
                  LEFT JOIN employees e ON lr.employee_id = e.id
                  LEFT JOIN leave_types lt ON lr.leave_type_id = lt.id
                  WHERE 1=1";
        
        $params = [];
        if (!empty($filters['department_id'])) {
            $query .= " AND e.department_id = :dept_id";
            $params[':dept_id'] = $filters['department_id'];
        }
        if (!empty($filters['search'])) {
            $query .= " AND (e.first_name LIKE :search OR e.last_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['date'])) {
            $query .= " AND (:date BETWEEN lr.start_date AND lr.end_date)";
            $params[':date'] = $filters['date'];
        }

        $query .= " ORDER BY lr.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
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
                    
                    // Fetch limit from the leave_type itself
                    $ltStmt = $this->conn->prepare("SELECT days_allowed FROM leave_types WHERE id = ?");
                    $ltStmt->execute([$leave['leave_type_id']]);
                    $limit = (int)$ltStmt->fetchColumn();

                    // Get total approved paid leaves for this specific leave type this year
                    $sumStmt = $this->conn->prepare("SELECT SUM(days) as total FROM " . $this->table . " WHERE employee_id = ? AND status = 'Approved' AND leave_type_id = ? AND YEAR(start_date) = ? AND id != ?");
                    $sumStmt->execute([$leave['employee_id'], $leave['leave_type_id'], $year, $id]);
                    $past_total = $sumStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

                    $new_total = $past_total + $leave['days'];
                    if ($limit < 999 && $new_total > $limit) {
                        $excess_days = $new_total - $limit;
                        if ($excess_days > $leave['days']) {
                            $excess_days = $leave['days'];
                        }

                        if ($excess_days > 0) {
                            $end_date_str = $leave['end_date'];
                            $start_date_ts = strtotime("-" . ($excess_days - 1) . " days", strtotime($end_date_str));
                            $start_date_str = date('Y-m-d', $start_date_ts);
                            $deduction->applyAutomatedRangeDeduction($leave['employee_id'], 'Unpaid Leave', $start_date_str, $end_date_str, $excess_days, 'Exceeded Paid Leave Limit (Unpaid Leave)', 'Leave Management System', $id, 'Active');
                        }
                    }
                } elseif ($leave && $leave['is_paid'] == 0) {
                    // Update pending deductions to active
                    $updDed = "UPDATE deductions SET status = 'Active', reason = 'Approved Unpaid Leave' WHERE related_id = ? AND type = 'Unpaid Leave'";
                    $updStmt = $this->conn->prepare($updDed);
                    $updStmt->execute([$id]);
                }
            } elseif ($action === 'reject') {
                // Cancel pending deductions
                $updDed = "UPDATE deductions SET status = 'Cancelled' WHERE related_id = ? AND type = 'Unpaid Leave'";
                $updStmt = $this->conn->prepare($updDed);
                $updStmt->execute([$id]);
            }

            // Get user_id for notification
            $q2 = "SELECT e.user_id FROM " . $this->table . " lr JOIN employees e ON lr.employee_id = e.id WHERE lr.id = :id";
            $s2 = $this->conn->prepare($q2);
            $s2->bindParam(':id', $id);
            $s2->execute();
            $row = $s2->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                require_once __DIR__ . '/Notification.php';
                $notif = new Notification();
                $notif->create($row['user_id'], "Your leave request has been {$status}.", 'leave', '/employee/leaves', 'Leave Approval');
            }
            return true;
        }
        return false;
    }
}
