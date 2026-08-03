<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();

try {
    $conn->beginTransaction();

    // 1. Add new deduction breakdown columns to payroll table
    $conn->exec("ALTER TABLE payroll ADD COLUMN leave_deduction_amount DECIMAL(10,2) DEFAULT 0.00 AFTER allowance_amount");
    $conn->exec("ALTER TABLE payroll ADD COLUMN late_deduction_amount DECIMAL(10,2) DEFAULT 0.00 AFTER leave_deduction_amount");
    $conn->exec("ALTER TABLE payroll ADD COLUMN other_deduction_amount DECIMAL(10,2) DEFAULT 0.00 AFTER late_deduction_amount");

    // 2. Add payment_method and payment_date columns
    $conn->exec("ALTER TABLE payroll ADD COLUMN payment_method VARCHAR(50) DEFAULT NULL AFTER status");
    $conn->exec("ALTER TABLE payroll ADD COLUMN payment_date DATETIME DEFAULT NULL AFTER payment_method");
    
    // 3. Update status ENUM to Pending and Paid. Note: existing 'Generated' will be mapped to 'Pending', 'Draft' to 'Pending'.
    // To do this safely, we first alter the ENUM to include all old and new, then update, then restrict.
    $conn->exec("ALTER TABLE payroll MODIFY COLUMN status ENUM('Draft','Generated','Paid','Pending') DEFAULT 'Pending'");
    $conn->exec("UPDATE payroll SET status = 'Pending' WHERE status IN ('Draft', 'Generated')");
    $conn->exec("ALTER TABLE payroll MODIFY COLUMN status ENUM('Pending','Paid') DEFAULT 'Pending'");

    $conn->commit();
    echo "SUCCESS: Payroll migration completed.";
} catch (Exception $e) {
    $conn->rollBack();
    echo "ERROR: " . $e->getMessage();
}
