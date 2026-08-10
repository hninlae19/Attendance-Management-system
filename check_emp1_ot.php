<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
print_r($conn->query('SELECT employee_id, month, ot_hours, ot_amount FROM payroll WHERE employee_id = 1 AND month = 8')->fetchAll(PDO::FETCH_ASSOC));
