<?php
require_once __DIR__ . '/../config/database.php';

class LeaveRequest {
    private $conn;
    private $table = 'LeaveRequest';

    public $RequestID;
    public $LeaveTypeID;
    public $EmpID;
    public $StartDate;
    public $EndDate;
    public $Reason;
    public $Status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET LeaveTypeID = :type, EmpID = :emp, StartDate = :start, EndDate = :end, Reason = :reason, Status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':type', $this->LeaveTypeID);
        $stmt->bindParam(':emp', $this->EmpID);
        $stmt->bindParam(':start', $this->StartDate);
        $stmt->bindParam(':end', $this->EndDate);
        $stmt->bindParam(':reason', $this->Reason);
        $stmt->bindParam(':status', $this->Status);
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT lr.*, lt.LeaveType, lt.IsPaid, e.FirstName, e.LastName, DATEDIFF(lr.EndDate, lr.StartDate) + 1 as days
                  FROM " . $this->table . " lr
                  LEFT JOIN LeaveTypes lt ON lr.LeaveTypeID = lt.LeaveTypeID
                  LEFT JOIN Employee e ON lr.EmpID = e.EmpID
                  ORDER BY lr.RequestID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByEmployee($emp_id) {
        $query = "SELECT lr.*, lt.LeaveType, lt.IsPaid, DATEDIFF(lr.EndDate, lr.StartDate) + 1 as days
                  FROM " . $this->table . " lr
                  LEFT JOIN LeaveTypes lt ON lr.LeaveTypeID = lt.LeaveTypeID
                  WHERE lr.EmpID = :emp_id
                  ORDER BY lr.RequestID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT lr.*, lt.LeaveType, e.FirstName, e.LastName 
                  FROM " . $this->table . " lr
                  LEFT JOIN LeaveTypes lt ON lr.LeaveTypeID = lt.LeaveTypeID
                  LEFT JOIN Employee e ON lr.EmpID = e.EmpID
                  WHERE lr.RequestID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET Status = :status WHERE RequestID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getUsedDays($emp_id, $type_id) {
        $query = "SELECT SUM(DATEDIFF(EndDate, StartDate) + 1) as used 
                  FROM " . $this->table . " 
                  WHERE EmpID = :emp_id AND LeaveTypeID = :type_id AND Status = 'Approved'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':type_id', $type_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['used'] ? (int)$result['used'] : 0;
    }
}
