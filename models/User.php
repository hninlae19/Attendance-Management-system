<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $email;
    public $password;
    public $role;
    public $status;


    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                if ($row['status'] === 'Active') {
                    $this->id = $row['id'];
                    $this->email = $row['email'];
                    $this->role = $row['role'];
                    return true;
                } else {
                    return "Account is inactive.";
                }
            }
        }
        return "Invalid email or password.";
    }

    public function getEmployeeProfile() {
        if ($this->role === 'Employee') {
            $query = "SELECT id as employee_id, first_name, last_name, employee_code FROM employees WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $this->id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
        return null;
    }
}
