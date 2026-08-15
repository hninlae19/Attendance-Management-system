<?php
require_once __DIR__ . '/../config/database.php';

class Payroll {
    private $conn;
    private $table = 'Payroll';

    public $PayrollID;
    public $EmpID;
    public $BasicSalary;
    public $PayrollMonth;
    public $BonousAmount;
    public $OvertimeAmount;
    public $LeaveDeductionAmount;
    public $NetSalary;
    public $Status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT p.*, e.FirstName, e.LastName, d.DeptName 
                  FROM " . $this->table . " p
                  LEFT JOIN Employee e ON p.EmpID = e.EmpID
                  LEFT JOIN Position pos ON e.PositionID = pos.PositionID
                  LEFT JOIN Department d ON pos.DeptID = d.DeptID
                  ORDER BY p.PayrollID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByEmployee($emp_id) {
        $query = "SELECT p.*, e.FirstName, e.LastName, pos.PositionName, d.DeptName 
                  FROM " . $this->table . " p
                  LEFT JOIN Employee e ON p.EmpID = e.EmpID
                  LEFT JOIN Position pos ON e.PositionID = pos.PositionID
                  LEFT JOIN Department d ON pos.DeptID = d.DeptID
                  WHERE p.EmpID = :emp_id 
                  ORDER BY p.PayrollID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT p.*, e.FirstName, e.LastName, pos.PositionName, d.DeptName 
                  FROM " . $this->table . " p
                  LEFT JOIN Employee e ON p.EmpID = e.EmpID
                  LEFT JOIN Position pos ON e.PositionID = pos.PositionID
                  LEFT JOIN Department d ON pos.DeptID = d.DeptID
                  WHERE p.PayrollID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
