<?php
require_once __DIR__ . '/../config/database.php';

class Employee {
    private $conn;
    private $table = 'Employee';

    public $EmpID;
    public $FirstName;
    public $LastName;
    public $Gender;
    public $Email;
    public $Password;
    public $PhoneNumber;
    public $Address;
    public $PositionID;
    public $JoinDate;
    public $Status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE Email = :email AND Status = 'Active'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['Password'])) {
                $this->EmpID = $row['EmpID'];
                $this->FirstName = $row['FirstName'];
                $this->LastName = $row['LastName'];
                return true;
            }
        }
        return false;
    }

    public function emailExists($email, $excludeEmpID = null) {
        $query = "SELECT EmpID FROM " . $this->table . " WHERE Email = :email";
        if ($excludeEmpID) {
            $query .= " AND EmpID != :exclude_id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($excludeEmpID) {
            $stmt->bindParam(':exclude_id', $excludeEmpID);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET Status = :status WHERE EmpID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT e.*, p.PositionName, p.DeptID, d.DeptName 
                  FROM " . $this->table . " e
                  LEFT JOIN Position p ON e.PositionID = p.PositionID
                  LEFT JOIN Department d ON p.DeptID = d.DeptID
                  ORDER BY e.EmpID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveEmployees() {
        $query = "SELECT e.*, p.PositionName, p.DeptID, d.DeptName 
                  FROM " . $this->table . " e
                  LEFT JOIN Position p ON e.PositionID = p.PositionID
                  LEFT JOIN Department d ON p.DeptID = d.DeptID
                  WHERE e.Status = 'Active'
                  ORDER BY e.FirstName, e.LastName";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmployeeById($id) {
        $query = "SELECT e.*, p.PositionName, p.BasicSalary, p.DeptID, d.DeptName 
                  FROM " . $this->table . " e
                  LEFT JOIN Position p ON e.PositionID = p.PositionID
                  LEFT JOIN Department d ON p.DeptID = d.DeptID
                  WHERE e.EmpID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  SET FirstName=:FirstName, LastName=:LastName, Gender=:Gender, 
                      Email=:Email, Password=:Password, PhoneNumber=:PhoneNumber, 
                      Address=:Address, PositionID=:PositionID, JoinDate=:JoinDate, Status=:Status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':FirstName', $this->FirstName);
        $stmt->bindParam(':LastName', $this->LastName);
        $stmt->bindParam(':Gender', $this->Gender);
        $stmt->bindParam(':Email', $this->Email);
        $stmt->bindParam(':Password', $this->Password);
        $stmt->bindParam(':PhoneNumber', $this->PhoneNumber);
        $stmt->bindParam(':Address', $this->Address);
        $stmt->bindParam(':PositionID', $this->PositionID);
        $stmt->bindParam(':JoinDate', $this->JoinDate);
        $stmt->bindParam(':Status', $this->Status);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET FirstName=:FirstName, LastName=:LastName, Gender=:Gender, 
                      Email=:Email, PhoneNumber=:PhoneNumber, 
                      Address=:Address, PositionID=:PositionID, JoinDate=:JoinDate, Status=:Status" . 
                  ($this->Password ? ", Password=:Password" : "") . 
                  " WHERE EmpID = :EmpID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':FirstName', $this->FirstName);
        $stmt->bindParam(':LastName', $this->LastName);
        $stmt->bindParam(':Gender', $this->Gender);
        $stmt->bindParam(':Email', $this->Email);
        if ($this->Password) {
            $stmt->bindParam(':Password', $this->Password);
        }
        $stmt->bindParam(':PhoneNumber', $this->PhoneNumber);
        $stmt->bindParam(':Address', $this->Address);
        $stmt->bindParam(':PositionID', $this->PositionID);
        $stmt->bindParam(':JoinDate', $this->JoinDate);
        $stmt->bindParam(':Status', $this->Status);
        $stmt->bindParam(':EmpID', $this->EmpID);
        return $stmt->execute();
    }
}
