<?php
require_once __DIR__ . '/config/Database.php';

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM deductions ORDER BY id DESC LIMIT 5");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($res, JSON_PRETTY_PRINT);
