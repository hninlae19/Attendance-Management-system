<?php
class OvertimeRequest {
    private $conn;
    private $table = 'overtime_requests';

    public $id;
    public $employee_id;
    public $date;
    public $start_time;
    public $end_time;
    public $hours;
    public $type;
    public $reason;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT orq.*, e.first_name, e.last_name, e.employee_code 
                  FROM " . $this->table . " orq
                  LEFT JOIN employees e ON orq.employee_id = e.id
                  ORDER BY orq.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount($filters = []) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " orq
                  LEFT JOIN employees e ON orq.employee_id = e.id
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
            $query .= " AND orq.date = :date";
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
        $query = "SELECT orq.*, e.first_name, e.last_name, e.employee_code 
                  FROM " . $this->table . " orq
                  LEFT JOIN employees e ON orq.employee_id = e.id
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
            $query .= " AND orq.date = :date";
            $params[':date'] = $filters['date'];
        }

        $query .= " ORDER BY orq.id DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handleRequest($id, $action) {
        $status = ($action === 'approve') ? 'Approved' : 'Rejected';
        
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            $q2 = "SELECT e.user_id FROM " . $this->table . " orq JOIN employees e ON orq.employee_id = e.id WHERE orq.id = :id";
            $s2 = $this->conn->prepare($q2);
            $s2->bindParam(':id', $id);
            $s2->execute();
            $row = $s2->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                require_once __DIR__ . '/Notification.php';
                $notif = new Notification();
                $notif->create($row['user_id'], "Your overtime request has been {$status}.", 'overtime', '/employee/overtime', 'Overtime Approval');
            }
            return true;
        }
        return false;
    }
}
