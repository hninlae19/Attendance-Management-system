<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT id, email, role, status, password FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
