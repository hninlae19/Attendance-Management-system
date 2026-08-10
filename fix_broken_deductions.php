<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$count = $conn->exec("UPDATE deductions SET status = 'Active' WHERE status = 'Applied' AND payroll_id IS NULL");
echo "Reset $count deductions.";
