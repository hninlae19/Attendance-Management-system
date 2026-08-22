<?php
require 'c:/wamp64/www/payrollsystem/config/database.php';
$db = new Database();
$conn = $db->getConnection();

try {
    $conn->exec("ALTER TABLE department ADD COLUMN Status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active'");
    $conn->exec("ALTER TABLE position ADD COLUMN Status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active'");
    
    echo "Database schema updated successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
