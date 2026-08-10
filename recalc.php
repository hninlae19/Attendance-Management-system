<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Payroll.php';

try {
    $payrollModel = new Payroll();
    $month = 8;
    $year = 2026;
    
    $count = $payrollModel->generatePayroll($month, $year);
    echo "Recalculated payroll for $count employees for month $month, year $year.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
