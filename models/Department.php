<?php
require_once __DIR__ . '/../config/database.php';

class Department {
    private $conn;
    private $table = 'Department';

    public $DeptID;
    public $DeptName;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY DeptName";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET DeptName = :name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->DeptName);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET DeptName = :name WHERE DeptID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->DeptName);
        $stmt->bindParam(':id', $this->DeptID);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE DeptID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function nameExists($name, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE DeptName = :name";
        if ($excludeId) {
            $query .= " AND DeptID != :id";
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
