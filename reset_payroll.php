<?php
require 'config/database.php';
require 'models/Payroll.php';
$db = new Database();
$conn = $db->getConnection();
// 1. Un-pay everything
$conn->exec("UPDATE payroll SET status = 'Pending'");
echo "Unpaid all payrolls.\n";

// 2. Regenerate everything
$p = new Payroll();
echo "Regenerated " . $p->generatePayroll(8, 2026) . " payrolls.\n";
