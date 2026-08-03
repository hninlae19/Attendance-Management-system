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
                  ORDER BY orq.created_at DESC";
        $stmt = $this->conn->prepare($query);
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
            $q2 = "SELECT employee_id FROM " . $this->table . " WHERE id = :id";
            $s2 = $this->conn->prepare($q2);
            $s2->bindParam(':id', $id);
            $s2->execute();
            $row = $s2->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                require_once __DIR__ . '/Notification.php';
                $notif = new Notification();
                $notif->create($row['employee_id'], "Your overtime request has been {$status}.", 'overtime', '/employee/overtime');
            }
            return true;
        }
        return false;
    }
}
