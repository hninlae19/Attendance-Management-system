<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query('DESCRIBE payroll');
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
