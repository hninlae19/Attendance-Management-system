<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
print_r($conn->query('SELECT employee_id, ot_hours, ot_amount FROM payroll')->fetchAll(PDO::FETCH_ASSOC));
