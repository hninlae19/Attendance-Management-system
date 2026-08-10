<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
print_r(array_keys($conn->query('SELECT * FROM payroll LIMIT 1')->fetch(PDO::FETCH_ASSOC)));
