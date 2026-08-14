<?php
require_once __DIR__ . '/../config/database.php';

class EmpBonous {
    private $conn;
    private $table = 'EmpBonous';

    public $EmpBonousID;
    public $BonousID;
    public $EmpID;
    public $BonusDate;
    public $Amount;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT eb.*, b.BonusType, e.FirstName, e.LastName, e.EmpID, d.DeptName 
                  FROM " . $this->table . " eb
                  LEFT JOIN Bonous b ON eb.BonousID = b.BonousID
                  LEFT JOIN Employee e ON eb.EmpID = e.EmpID
                  LEFT JOIN Position p ON e.PositionID = p.PositionID
                  LEFT JOIN Department d ON p.DeptID = d.DeptID
                  ORDER BY eb.EmpBonousID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (BonousID, EmpID, BonusDate, Amount) 
                  VALUES (:bonus_id, :emp_id, :bonus_date, :amount)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':bonus_id', $this->BonousID);
        $stmt->bindParam(':emp_id', $this->EmpID);
        $stmt->bindParam(':bonus_date', $this->BonusDate);
        $stmt->bindParam(':amount', $this->Amount);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE EmpBonousID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
