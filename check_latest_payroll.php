<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
print_r($conn->query('SELECT employee_id, month, ot_hours, ot_amount FROM payroll ORDER BY created_at DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC));
