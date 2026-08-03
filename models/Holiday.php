<?php
class Holiday {
    private $conn;
    private $table = 'holidays';

    public $id;
    public $name;
    public $date;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET name=:name, date=:date";
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":date", $this->date);
        
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET name=:name, date=:date WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":date", $this->date);
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

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function isHoliday($date) {
        $query = "SELECT id FROM " . $this->table . " WHERE date = :date LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
