<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT Email FROM admin");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
