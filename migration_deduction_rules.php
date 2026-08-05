<?php
require_once __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    $conn->beginTransaction();

    // 1. Update settings table
    $stmt1 = $conn->prepare("ALTER TABLE settings 
        ADD COLUMN unpaid_leave_deduction_rate DECIMAL(5,2) NOT NULL DEFAULT 1.00 AFTER half_day_deduction_rate,
        ADD COLUMN auto_deduction_enabled TINYINT(1) NOT NULL DEFAULT 1 AFTER unpaid_leave_deduction_rate,
        ADD COLUMN deduction_calculation_method ENUM('Fixed Amount', 'Salary-Based') NOT NULL DEFAULT 'Salary-Based' AFTER auto_deduction_enabled");
    $stmt1->execute();
    echo "Updated settings table.\n";

    // 2. Update deductions table
    $stmt2 = $conn->prepare("ALTER TABLE deductions 
        ADD COLUMN type VARCHAR(100) NOT NULL AFTER amount,
        ADD COLUMN status ENUM('Active', 'Cancelled', 'Applied') NOT NULL DEFAULT 'Active' AFTER date,
        ADD COLUMN source VARCHAR(100) NOT NULL AFTER status");
    $stmt2->execute();
    echo "Updated deductions table.\n";

    $conn->commit();
    echo "Migration completed successfully.\n";
} catch (Exception $e) {
    // Note: ALTER TABLE causes implicit commit, so rollback might fail or not apply to schema changes.
    echo "Migration finished with message: " . $e->getMessage() . "\n";
}
