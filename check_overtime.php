<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
echo "--- OVERTIME REQUESTS ---\n";
print_r($conn->query('SELECT * FROM overtime_requests')->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- OVERTIME ASSIGNMENTS ---\n";
print_r($conn->query('SELECT * FROM overtime_assignments')->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- OVERTIME ASSIGNMENT EMPLOYEES ---\n";
print_r($conn->query('SELECT * FROM overtime_assignment_employees')->fetchAll(PDO::FETCH_ASSOC));
