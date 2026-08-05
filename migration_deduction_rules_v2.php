<?php
require_once __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Update settings table
    $stmt1 = $conn->prepare("ALTER TABLE settings 
        ADD COLUMN unpaid_leave_deduction_rate DECIMAL(5,2) NOT NULL DEFAULT 1.00,
        ADD COLUMN auto_deduction_enabled TINYINT(1) NOT NULL DEFAULT 1,
        ADD COLUMN deduction_calculation_method ENUM('Fixed Amount', 'Salary-Based') NOT NULL DEFAULT 'Salary-Based'");
    $stmt1->execute();
    echo "Updated settings table.\n";
} catch (Exception $e) {
    echo "Settings update skipped/failed: " . $e->getMessage() . "\n";
}

try {
    // 2. Update deductions table
    $stmt2 = $conn->prepare("ALTER TABLE deductions 
        MODIFY COLUMN status ENUM('Active', 'Pending', 'Applied', 'Cancelled') NOT NULL DEFAULT 'Active',
        ADD COLUMN source VARCHAR(100) NOT NULL DEFAULT 'System'");
    $stmt2->execute();
    echo "Updated deductions table.\n";
} catch (Exception $e) {
    echo "Deductions update skipped/failed: " . $e->getMessage() . "\n";
}
echo "Migration completed.\n";
