<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
print_r($conn->query('SELECT employee_id, month, status FROM payroll WHERE employee_id = 1')->fetchAll(PDO::FETCH_ASSOC));
