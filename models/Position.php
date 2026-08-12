<?php
class Position {
    private $conn;
    private $table = 'positions';

    public $id;
    public $name;
    public $department_id;
    public $basic_salary;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT p.*, d.name as department_name FROM " . $this->table . " p 
                  LEFT JOIN departments d ON p.department_id = d.id 
                  ORDER BY p.name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET name=:name, department_id=:department_id, basic_salary=:basic_salary";
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->department_id = htmlspecialchars(strip_tags($this->department_id));
        $this->basic_salary = htmlspecialchars(strip_tags($this->basic_salary ?? '0'));
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":department_id", $this->department_id);
        $stmt->bindParam(":basic_salary", $this->basic_salary);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET name=:name, department_id=:department_id, basic_salary=:basic_salary WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->department_id = htmlspecialchars(strip_tags($this->department_id));
        $this->basic_salary = htmlspecialchars(strip_tags($this->basic_salary ?? '0'));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":department_id", $this->department_id);
        $stmt->bindParam(":basic_salary", $this->basic_salary);
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }
}
