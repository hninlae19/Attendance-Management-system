<?php
require 'c:/wamp64/www/payrollsystem/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM attendance WHERE EmpID = 1 AND AttendanceDate = '2026-08-19'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
