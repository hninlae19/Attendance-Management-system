<?php
require 'c:/wamp64/www/payrollsystem/config/Database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query('ALTER TABLE positions ADD basic_salary DECIMAL(10,2) DEFAULT 0.00');
echo 'Added column successfully';
