<?php
require_once __DIR__ . '/../config/database.php';

class Admin {
    private $conn;
    private $table = 'Admin';

    public $AdminID;
    public $Email;
    public $Password;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE Email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['Password'])) {
                $this->AdminID = $row['AdminID'];
                $this->Email = $row['Email'];
                return true;
            }
        }
        return false;
    }
}
