<?php
require_once __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    $conn->exec("ALTER TABLE OvertimeAssign MODIFY OTRate DECIMAL(12,2) NOT NULL");
    $conn->exec("ALTER TABLE OvertimeAssign MODIFY OTAmount DECIMAL(12,2) NOT NULL");
    echo "Successfully updated column types for OTRate and OTAmount.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
