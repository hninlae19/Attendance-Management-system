<?php
require_once __DIR__ . '/../config/database.php';

class Position {
    private $conn;
    private $table = 'Position';

    public $PositionID;
    public $PositionName;
    public $DeptID;
    public $BasicSalary;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT p.*, d.DeptName FROM " . $this->table . " p
                  LEFT JOIN Department d ON p.DeptID = d.DeptID
                  ORDER BY p.PositionID ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET PositionName = :name, DeptID = :dept, BasicSalary = :salary";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->PositionName);
        $stmt->bindParam(':dept', $this->DeptID);
        $stmt->bindParam(':salary', $this->BasicSalary);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET PositionName = :name, DeptID = :dept, BasicSalary = :salary
                  WHERE PositionID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->PositionName);
        $stmt->bindParam(':dept', $this->DeptID);
        $stmt->bindParam(':salary', $this->BasicSalary);
        $stmt->bindParam(':id', $this->PositionID);
        return $stmt->execute();
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE PositionID = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function hasEmployees($id) {
        $query = "SELECT COUNT(*) FROM Employee WHERE PositionID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function nameExists($name, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE PositionName = :name";
        if ($excludeId) {
            $query .= " AND PositionID != :id";
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
