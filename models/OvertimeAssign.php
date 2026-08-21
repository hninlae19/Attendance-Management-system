<?php
require_once __DIR__ . '/../config/database.php';

class OvertimeAssign {
    private $conn;
    private $table = 'OvertimeAssign';

    public $OvertimeID;
    public $EmpID;
    public $OvertimeDate;
    public $StartTime;
    public $EndTime;
    public $TotalHours;
    public $RateMultiplier;
    public $OTAmount;
    public $Status;
    public $ApprovedBy;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT oa.*, e.FirstName, e.LastName
                  FROM " . $this->table . " oa
                  LEFT JOIN Employee e ON oa.EmpID = e.EmpID
                  ORDER BY oa.OvertimeID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE Status = 'Pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (EmpID, OvertimeDate, StartTime, EndTime, TotalHours, RateMultiplier, OTAmount, Status, ApprovedBy) 
                  VALUES (:emp_id, :overtime_date, :start_time, :end_time, :hours, :rate, :amount, :status, :approved_by)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $this->EmpID);
        $stmt->bindParam(':overtime_date', $this->OvertimeDate);
        $stmt->bindParam(':start_time', $this->StartTime);
        $stmt->bindParam(':end_time', $this->EndTime);
        $stmt->bindParam(':hours', $this->TotalHours);
        $stmt->bindParam(':rate', $this->RateMultiplier);
        $stmt->bindParam(':amount', $this->OTAmount);
        
        $status = $this->Status ?? 'Pending';
        $stmt->bindParam(':status', $status);
        
        $approvedBy = $this->ApprovedBy ?? null;
        $stmt->bindParam(':approved_by', $approvedBy);
        
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET EmpID = :emp_id, OvertimeDate = :overtime_date, 
                      StartTime = :start_time, EndTime = :end_time,
                      TotalHours = :hours, RateMultiplier = :rate, OTAmount = :amount 
                  WHERE OvertimeID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $this->EmpID);
        $stmt->bindParam(':overtime_date', $this->OvertimeDate);
        $stmt->bindParam(':start_time', $this->StartTime);
        $stmt->bindParam(':end_time', $this->EndTime);
        $stmt->bindParam(':hours', $this->TotalHours);
        $stmt->bindParam(':rate', $this->RateMultiplier);
        $stmt->bindParam(':amount', $this->OTAmount);
        $stmt->bindParam(':id', $this->OvertimeID);
        return $stmt->execute();
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE OvertimeID = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getByEmployee($emp_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE EmpID = :emp_id 
                  ORDER BY OvertimeID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpcomingByEmployee($emp_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE EmpID = :emp_id AND OvertimeDate >= CURRENT_DATE()
                  ORDER BY OvertimeDate ASC, StartTime ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyHours($emp_id, $year, $month, $exclude_id = null) {
        $query = "SELECT SUM(TotalHours) as total_hours FROM " . $this->table . " 
                  WHERE EmpID = :emp_id AND YEAR(OvertimeDate) = :year AND MONTH(OvertimeDate) = :month AND Status = 'Completed'";
        if ($exclude_id) {
            $query .= " AND OvertimeID != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);
        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_hours'] ? (float)$row['total_hours'] : 0.0;
    }

    public function getAssignmentsByDate($emp_id, $date, $exclude_id = null) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE EmpID = :emp_id AND OvertimeDate = :date";
        if ($exclude_id) {
            $query .= " AND OvertimeID != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':date', $date);
        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE OvertimeID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $approvedBy = null) {
        $query = "UPDATE " . $this->table . " SET Status = :status";
        if ($approvedBy) {
            $query .= ", ApprovedBy = :app_by";
        }
        $query .= " WHERE OvertimeID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        if ($approvedBy) {
            $stmt->bindParam(':app_by', $approvedBy);
        }
        return $stmt->execute();
    }

    public function accept($id, $empId) {
        $query = "UPDATE " . $this->table . " SET Status = 'Accepted' WHERE OvertimeID = :id AND EmpID = :emp_id AND Status = 'Pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $empId);
        return $stmt->execute();
    }

    public function reject($id, $empId) {
        $query = "UPDATE " . $this->table . " SET Status = 'Rejected' WHERE OvertimeID = :id AND EmpID = :emp_id AND Status = 'Pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $empId);
        return $stmt->execute();
    }

    public function checkIn($id, $empId) {
        // Can only check in if Accepted and within valid time range (-10 mins before StartTime)
        // Wait, the validation logic is better off being handled partially in PHP or directly in SQL. 
        // We'll let SQL handle the time boundary to be safe: 
        // StartTime <= NOW() + INTERVAL 10 MINUTE and EndTime > NOW()
        
        $query = "UPDATE " . $this->table . " 
                  SET Status = 'InProgress' 
                  WHERE OvertimeID = :id AND EmpID = :emp_id AND Status = 'Accepted' 
                  AND (StartTime - INTERVAL 10 MINUTE) <= NOW() AND EndTime > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $empId);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
