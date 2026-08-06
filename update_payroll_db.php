<?php
require_once 'config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Adding ot_hours
    try {
        $conn->exec("ALTER TABLE payroll ADD COLUMN ot_hours decimal(10,2) NOT NULL DEFAULT '0.00' AFTER ot_amount");
        echo "Added ot_hours.\n";
    } catch (PDOException $e) {
        echo "ot_hours might already exist: " . $e->getMessage() . "\n";
    }

    // Adding leave_deduction_amount
    try {
        $conn->exec("ALTER TABLE payroll ADD COLUMN leave_deduction_amount decimal(10,2) DEFAULT '0.00' AFTER allowance_amount");
        echo "Added leave_deduction_amount.\n";
    } catch (PDOException $e) {
        echo "leave_deduction_amount might already exist: " . $e->getMessage() . "\n";
    }

    // Adding late_deduction_amount
    try {
        $conn->exec("ALTER TABLE payroll ADD COLUMN late_deduction_amount decimal(10,2) DEFAULT '0.00' AFTER leave_deduction_amount");
        echo "Added late_deduction_amount.\n";
    } catch (PDOException $e) {
        echo "late_deduction_amount might already exist: " . $e->getMessage() . "\n";
    }
    
    // Adding other_deduction_amount
    try {
        $conn->exec("ALTER TABLE payroll ADD COLUMN other_deduction_amount decimal(10,2) DEFAULT '0.00' AFTER late_deduction_amount");
        echo "Added other_deduction_amount.\n";
    } catch (PDOException $e) {
        echo "other_deduction_amount might already exist: " . $e->getMessage() . "\n";
    }

    // Delete pending payrolls
    try {
        $conn->exec("DELETE FROM payroll WHERE status = 'Pending'");
        echo "Deleted pending payrolls.\n";
    } catch (PDOException $e) {
        echo "Failed to delete pending payrolls: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
