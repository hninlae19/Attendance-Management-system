<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SHOW COLUMNS FROM overtimeassign");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
