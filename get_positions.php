<?php
require 'c:/wamp64/www/payrollsystem/config/Database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query('DESCRIBE positions');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
