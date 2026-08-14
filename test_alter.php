<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

$queries = [
    "ALTER TABLE payroll ADD COLUMN employee_code VARCHAR(50) NULL AFTER EmpID",
    "ALTER TABLE payroll ADD COLUMN present_days INT DEFAULT 0 AFTER BasicSalary",
    "ALTER TABLE payroll ADD COLUMN leave_days INT DEFAULT 0 AFTER present_days",
    "ALTER TABLE payroll ADD COLUMN absent_days INT DEFAULT 0 AFTER leave_days",
    "ALTER TABLE payroll ADD COLUMN half_days INT DEFAULT 0 AFTER absent_days",
    "ALTER TABLE payroll ADD COLUMN late_days INT DEFAULT 0 AFTER half_days",
    "ALTER TABLE payroll ADD COLUMN ot_hours DECIMAL(10,2) DEFAULT 0 AFTER late_days",
    "ALTER TABLE payroll ADD COLUMN bonus_amount DECIMAL(10,2) DEFAULT 0 AFTER ot_amount",
    "ALTER TABLE payroll ADD COLUMN late_deduction_amount DECIMAL(10,2) DEFAULT 0 AFTER leave_deduction_amount",
    "ALTER TABLE payroll ADD COLUMN other_deduction_amount DECIMAL(10,2) DEFAULT 0 AFTER late_deduction_amount",
    "ALTER TABLE payroll ADD COLUMN gross_salary DECIMAL(10,2) DEFAULT 0 AFTER other_deduction_amount"
];

foreach ($queries as $q) {
    try {
        $conn->exec($q);
        echo "Executed: $q\n";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
