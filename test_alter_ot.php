<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
try {
    $conn->exec("ALTER TABLE overtimeassign ADD COLUMN StartTime TIME NULL AFTER OvertimeDate");
    echo "StartTime added.\n";
} catch(PDOException $e) {
    echo "Error adding StartTime: " . $e->getMessage() . "\n";
}
try {
    $conn->exec("ALTER TABLE overtimeassign ADD COLUMN EndTime TIME NULL AFTER StartTime");
    echo "EndTime added.\n";
} catch(PDOException $e) {
    echo "Error adding EndTime: " . $e->getMessage() . "\n";
}
