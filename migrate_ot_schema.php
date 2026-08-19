<?php
require 'c:/wamp64/www/payrollsystem/config/database.php';
$db = new Database();
$conn = $db->getConnection();

try {
    // Modify ENUM column
    $conn->exec("ALTER TABLE overtimeassign MODIFY COLUMN Status enum('Pending','Accepted','Rejected','InProgress','Completed','NoOT') NOT NULL DEFAULT 'Pending'");
    
    // Migrate any 'Approved' to 'Completed' (assuming that's the intention if they are past EndTime, or just map them directly so it doesn't crash)
    $conn->exec("UPDATE overtimeassign SET Status = 'Completed' WHERE Status NOT IN ('Pending','Accepted','Rejected','InProgress','Completed','NoOT')");
    
    echo "Database schema updated successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
