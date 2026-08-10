<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
print_r($conn->query('SELECT id, employee_id, amount, date, type, status FROM deductions')->fetchAll(PDO::FETCH_ASSOC));
