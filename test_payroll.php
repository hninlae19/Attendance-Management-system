<?php
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query('SHOW COLUMNS FROM Payroll');
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result, JSON_PRETTY_PRINT);
