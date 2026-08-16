<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query('SELECT id, link FROM notifications');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
