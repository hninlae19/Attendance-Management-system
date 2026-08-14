<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("DESCRIBE bonous");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($columns);
