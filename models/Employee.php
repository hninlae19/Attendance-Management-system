<?php
class Employee {
    private $conn;
    private $table = 'employees';

    public $id;
    public $user_id;
    public $employee_code;
    public $first_name;
    public $last_name;
    public $department_id;
    public $position_id;
    public $basic_salary;
    public $join_date;
    public $phone;
    public $address;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT e.*, d.name as department_name, p.name as position_name, u.email, u.status 
                  FROM " . $this->table . " e
                  LEFT JOIN departments d ON e.department_id = d.id
                  LEFT JOIN positions p ON e.position_id = p.id
                  LEFT JOIN users u ON e.user_id = u.id
                  ORDER BY e.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $query = "SELECT e.*, d.name as department_name, p.name as position_name, u.email, u.status 
                  FROM " . $this->table . " e
                  LEFT JOIN departments d ON e.department_id = d.id
                  LEFT JOIN positions p ON e.position_id = p.id
                  LEFT JOIN users u ON e.user_id = u.id
                  WHERE e.user_id = :user_id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT e.*, d.name as department_name, p.name as position_name, u.email, u.status 
                  FROM " . $this->table . " e
                  LEFT JOIN departments d ON e.department_id = d.id
                  LEFT JOIN positions p ON e.position_id = p.id
                  LEFT JOIN users u ON e.user_id = u.id
                  WHERE e.id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($email, $password) {
        try {
            $this->conn->beginTransaction();

            // 1. Create User
            $queryUser = "INSERT INTO users SET email=:email, password=:password, role='Employee', status='Active'";
            $stmtUser = $this->conn->prepare($queryUser);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmtUser->bindParam(":email", $email);
            $stmtUser->bindParam(":password", $hashedPassword);
            
            if(!$stmtUser->execute()) {
                throw new Exception("Could not create user account.");
            }
            
            $this->user_id = $this->conn->lastInsertId();

            // Generate Employee Code
            if ($this->employee_code === 'AUTO' || empty($this->employee_code)) {
                $stmtCode = $this->conn->query("SELECT MAX(CAST(employee_code AS UNSIGNED)) as max_code FROM " . $this->table);
                $maxRow = $stmtCode->fetch(PDO::FETCH_ASSOC);
                $nextCode = intval($maxRow['max_code'] ?? 0) + 1;
                $this->employee_code = str_pad($nextCode, 4, '0', STR_PAD_LEFT);
            }

            // 2. Create Employee
            $queryEmp = "INSERT INTO " . $this->table . " SET 
                        user_id=:user_id,
                        employee_code=:employee_code,
                        first_name=:first_name,
                        last_name=:last_name,
                        department_id=:department_id,
                        position_id=:position_id,
                        basic_salary=:basic_salary,
                        join_date=:join_date,
                        phone=:phone,
                        address=:address";
                        
            $stmtEmp = $this->conn->prepare($queryEmp);
            
            // Clean data
            $this->employee_code = htmlspecialchars(strip_tags($this->employee_code));
            $this->first_name = htmlspecialchars(strip_tags($this->first_name));
            $this->last_name = htmlspecialchars(strip_tags($this->last_name));
            $this->phone = htmlspecialchars(strip_tags($this->phone));
            $this->address = htmlspecialchars(strip_tags($this->address));
            
            $stmtEmp->bindParam(":user_id", $this->user_id);
            $stmtEmp->bindParam(":employee_code", $this->employee_code);
            $stmtEmp->bindParam(":first_name", $this->first_name);
            $stmtEmp->bindParam(":last_name", $this->last_name);
            $stmtEmp->bindParam(":department_id", $this->department_id);
            $stmtEmp->bindParam(":position_id", $this->position_id);
            $stmtEmp->bindParam(":basic_salary", $this->basic_salary);
            $stmtEmp->bindParam(":join_date", $this->join_date);
            $stmtEmp->bindParam(":phone", $this->phone);
            $stmtEmp->bindParam(":address", $this->address);
            
            if(!$stmtEmp->execute()) {
                throw new Exception("Could not create employee profile.");
            }

            $this->conn->commit();
            return true;

        } catch(Exception $e) {
            $this->conn->rollBack();
            return $e->getMessage();
        }
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET 
                    first_name=:first_name,
                    last_name=:last_name,
                    department_id=:department_id,
                    position_id=:position_id,
                    basic_salary=:basic_salary,
                    join_date=:join_date,
                    phone=:phone,
                    address=:address
                  WHERE id=:id";
                    
        $stmt = $this->conn->prepare($query);
        
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));
        
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":department_id", $this->department_id);
        $stmt->bindParam(":position_id", $this->position_id);
        $stmt->bindParam(":basic_salary", $this->basic_salary);
        $stmt->bindParam(":join_date", $this->join_date);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        
        $result = $stmt->execute();
        
        if ($result && !empty($this->status)) {
            $stmtUserQuery = $this->conn->prepare("SELECT user_id FROM " . $this->table . " WHERE id = :id");
            $stmtUserQuery->bindParam(":id", $this->id);
            $stmtUserQuery->execute();
            $rowUser = $stmtUserQuery->fetch(PDO::FETCH_ASSOC);
            if ($rowUser) {
                $stmtUserUpdate = $this->conn->prepare("UPDATE users SET status = :status WHERE id = :user_id");
                $stmtUserUpdate->bindParam(":status", $this->status);
                $stmtUserUpdate->bindParam(":user_id", $rowUser['user_id']);
                $stmtUserUpdate->execute();
            }
        }
        
        return $result;
    }

    public function delete() {
        try {
            $this->conn->beginTransaction();
            
            // Get user_id first
            $stmt = $this->conn->prepare("SELECT user_id FROM " . $this->table . " WHERE id = :id");
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $stmtEmp = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
                $stmtEmp->bindParam(":id", $this->id);
                $stmtEmp->execute();
                
                $stmtUser = $this->conn->prepare("DELETE FROM users WHERE id = :user_id");
                $stmtUser->bindParam(":user_id", $row['user_id']);
                $stmtUser->execute();
            }
            
            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
