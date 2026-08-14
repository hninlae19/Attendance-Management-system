<?php
require_once __DIR__ . '/../config/database.php';

class Bonous {
    private $conn;
    private $table = 'Bonous';

    public $BonousID;
    public $BonusType;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY BonusType";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
