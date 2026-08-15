<?php
require_once __DIR__ . '/../config/database.php';

class OvertimeAssign {
    private $conn;
    private $table = 'OvertimeAssign';

    public $OvertimeID;
    public $EmpID;
    public $OvertimeDate;
    public $OvertimeHours;
    public $OTRate;
    public $OTAmount;
    public $StartTime;
    public $EndTime;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT oa.*, e.FirstName, e.LastName
                  FROM " . $this->table . " oa
                  LEFT JOIN Employee e ON oa.EmpID = e.EmpID
                  ORDER BY oa.OvertimeDate DESC, oa.OvertimeID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (EmpID, OvertimeDate, StartTime, EndTime, OvertimeHours, OTRate, OTAmount) 
                  VALUES (:emp_id, :overtime_date, :start_time, :end_time, :hours, :rate, :amount)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $this->EmpID);
        $stmt->bindParam(':overtime_date', $this->OvertimeDate);
        $stmt->bindParam(':start_time', $this->StartTime);
        $stmt->bindParam(':end_time', $this->EndTime);
        $stmt->bindParam(':hours', $this->OvertimeHours);
        $stmt->bindParam(':rate', $this->OTRate);
        $stmt->bindParam(':amount', $this->OTAmount);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET EmpID = :emp_id, OvertimeDate = :overtime_date, 
                      StartTime = :start_time, EndTime = :end_time,
                      OvertimeHours = :hours, OTRate = :rate, OTAmount = :amount 
                  WHERE OvertimeID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $this->EmpID);
        $stmt->bindParam(':overtime_date', $this->OvertimeDate);
        $stmt->bindParam(':start_time', $this->StartTime);
        $stmt->bindParam(':end_time', $this->EndTime);
        $stmt->bindParam(':hours', $this->OvertimeHours);
        $stmt->bindParam(':rate', $this->OTRate);
        $stmt->bindParam(':amount', $this->OTAmount);
        $stmt->bindParam(':id', $this->OvertimeID);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE OvertimeID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getByEmployee($emp_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE EmpID = :emp_id 
                  ORDER BY OvertimeDate DESC, OvertimeID DESC";
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
        $query = "SELECT SUM(OvertimeHours) as total_hours FROM " . $this->table . " 
                  WHERE EmpID = :emp_id AND YEAR(OvertimeDate) = :year AND MONTH(OvertimeDate) = :month";
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
            $query .= ", ApprovedBy = :app_by, ApprovedAt = NOW()";
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

    public function acceptReject($id, $emp_id, $status, $response = null) {
        $query = "UPDATE " . $this->table . " 
                  SET Status = :status, EmployeeResponse = :resp, AcceptedAt = NOW() 
                  WHERE OvertimeID = :id AND EmpID = :emp_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':resp', $response);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $emp_id);
        return $stmt->execute();
    }

    public function otCheckIn($id, $emp_id, $datetime) {
        $query = "UPDATE " . $this->table . " 
                  SET Status = 'In Progress', OTCheckIn = :dt 
                  WHERE OvertimeID = :id AND EmpID = :emp_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':dt', $datetime);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $emp_id);
        return $stmt->execute();
    }

    public function otCheckOut($id, $emp_id, $datetime, $actual_hours) {
        $query = "UPDATE " . $this->table . " 
                  SET Status = 'Completed', OTCheckOut = :dt, ActualOTHours = :ah 
                  WHERE OvertimeID = :id AND EmpID = :emp_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':dt', $datetime);
        $stmt->bindParam(':ah', $actual_hours);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $emp_id);
        return $stmt->execute();
    }

    public function processNoShows() {
        // Any accepted OT where the scheduled EndTime has passed, but hasn't been checked in
        $query = "UPDATE " . $this->table . " 
                  SET Status = 'No Show' 
                  WHERE Status = 'Accepted' 
                  AND CONCAT(OvertimeDate, ' ', EndTime) < NOW() 
                  AND OTCheckIn IS NULL";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
