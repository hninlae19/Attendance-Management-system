<?php
require_once __DIR__ . '/../config/database.php';

class LeaveType {
    private $conn;
    private $table = 'LeaveTypes';

    public $LeaveTypeID;
    public $LeaveType;
    public $DaysAllowed;
    public $IsPaid;
    public $DeductionRate;
    public $DurationMonths;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET LeaveType = :name, DaysAllowed = :days, IsPaid = :paid, DeductionRate = :rate, DurationMonths = :duration";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->LeaveType);
        $stmt->bindParam(':days', $this->DaysAllowed);
        $stmt->bindParam(':paid', $this->IsPaid);
        $stmt->bindParam(':rate', $this->DeductionRate);
        $stmt->bindParam(':duration', $this->DurationMonths);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET LeaveType = :name, DaysAllowed = :days, IsPaid = :paid, DeductionRate = :rate, DurationMonths = :duration 
                  WHERE LeaveTypeID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->LeaveType);
        $stmt->bindParam(':days', $this->DaysAllowed);
        $stmt->bindParam(':paid', $this->IsPaid);
        $stmt->bindParam(':rate', $this->DeductionRate);
        $stmt->bindParam(':duration', $this->DurationMonths);
        $stmt->bindParam(':id', $this->LeaveTypeID);
        return $stmt->execute();
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE LeaveTypeID = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function hasUsage($id) {
        $query = "SELECT (
            (SELECT COUNT(*) FROM LeaveRequests WHERE LeaveTypeID = :id1) + 
            (SELECT COUNT(*) FROM LeaveBalances WHERE LeaveTypeID = :id2)
        ) as total";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id1', $id);
        $stmt->bindParam(':id2', $id);
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }

    public function nameExists($name, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE LeaveType = :name";
        if ($excludeId) {
            $query .= " AND LeaveTypeID != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
