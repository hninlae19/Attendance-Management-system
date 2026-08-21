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

    public function autoUpdateStatuses() {
        $now = date('Y-m-d H:i:s');

        // 1. Pending OT not accepted or rejected 30 minutes before StartTime -> NoOT
        $query1 = "UPDATE " . $this->table . " 
                   SET Status = 'NoOT' 
                   WHERE Status = 'Pending' 
                   AND :now1 >= (StartTime - INTERVAL 30 MINUTE)";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam(':now1', $now);
        $stmt1->execute();

        // 2. Missed Check-in: Accepted but not checked in by 30 mins after StartTime -> NoOT
        $query2 = "UPDATE " . $this->table . " 
                   SET Status = 'NoOT' 
                   WHERE Status IN ('Accepted', 'Assigned') 
                   AND :now2 > (StartTime + INTERVAL 30 MINUTE)";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(':now2', $now);
        $stmt2->execute();

        // 3. Auto-Complete: InProgress and past EndTime -> Completed
        $query3 = "UPDATE " . $this->table . " 
                   SET Status = 'Completed' 
                   WHERE Status = 'InProgress' 
                   AND :now3 >= EndTime";
        $stmt3 = $this->conn->prepare($query3);
        $stmt3->bindParam(':now3', $now);
        $stmt3->execute();
    }

    public function getAll() {
        $this->autoUpdateStatuses();
        $query = "SELECT oa.*, e.FirstName, e.LastName
                  FROM " . $this->table . " oa
                  LEFT JOIN Employee e ON oa.EmpID = e.EmpID
                  ORDER BY oa.OvertimeID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingCount() {
        $this->autoUpdateStatuses();
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
        $this->autoUpdateStatuses();
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE EmpID = :emp_id 
                  ORDER BY OvertimeID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpcomingByEmployee($emp_id) {
        $this->autoUpdateStatuses();
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
        $this->autoUpdateStatuses();
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
        $this->autoUpdateStatuses();
        $query = "UPDATE " . $this->table . " SET Status = 'Accepted' WHERE OvertimeID = :id AND EmpID = :emp_id AND Status = 'Pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $empId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function reject($id, $empId) {
        $this->autoUpdateStatuses();
        $query = "UPDATE " . $this->table . " SET Status = 'Rejected' WHERE OvertimeID = :id AND EmpID = :emp_id AND Status = 'Pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $empId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function checkIn($id, $empId) {
        $ot = $this->getById($id);
        if (!$ot || $ot['EmpID'] != $empId) {
            return false;
        }

        // Allow check-in for Accepted, Approved, or Assigned overtime
        $allowedStatuses = ['Accepted', 'Approved', 'Assigned'];
        if (!in_array($ot['Status'], $allowedStatuses)) {
            return false;
        }

        $rawStart = trim($ot['StartTime'] ?? '');
        $rawEnd = trim($ot['EndTime'] ?? '');
        if (empty($rawStart) || empty($rawEnd)) {
            return false;
        }

        // Robust timestamp parsing whether stored as datetime or time
        if (strpos($rawStart, '-') !== false || strpos($rawStart, ' ') !== false) {
            $startTime = strtotime($rawStart);
        } else {
            $startTime = strtotime($ot['OvertimeDate'] . ' ' . $rawStart);
        }

        if (strpos($rawEnd, '-') !== false || strpos($rawEnd, ' ') !== false) {
            $endTime = strtotime($rawEnd);
        } else {
            $endTime = strtotime($ot['OvertimeDate'] . ' ' . $rawEnd);
        }

        if ($endTime <= $startTime) {
            $endTime += 86400; // Overnight shift across midnight
        }

        $now = time();

        // Must be within 10 minutes prior to StartTime until EndTime
        if ($now < ($startTime - 600) || $now > $endTime) {
            return false;
        }

        $query = "UPDATE " . $this->table . " 
                  SET Status = 'InProgress' 
                  WHERE OvertimeID = :id AND EmpID = :emp_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':emp_id', $empId);
        return $stmt->execute();
    }
}
