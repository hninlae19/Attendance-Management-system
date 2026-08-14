<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM payroll WHERE EmpID = 4");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
