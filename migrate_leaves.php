<?php
require_once __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Add gender to employees (ignore if already exists)
    try {
        $conn->exec("ALTER TABLE employees ADD COLUMN gender ENUM('Male', 'Female', 'Other') NOT NULL DEFAULT 'Other' AFTER last_name");
        echo "Added gender to employees.\n";
    } catch(PDOException $e) {
        echo "Error or already exists (employees.gender): " . $e->getMessage() . "\n";
    }

    // 2. Add columns to leave_types
    try {
        $conn->exec("ALTER TABLE leave_types 
            ADD COLUMN service_period_months INT NOT NULL DEFAULT 0 AFTER is_paid,
            ADD COLUMN gender_restriction ENUM('All', 'Male', 'Female') NOT NULL DEFAULT 'All' AFTER service_period_months,
            ADD COLUMN carry_forward TINYINT(1) NOT NULL DEFAULT 0 AFTER gender_restriction,
            ADD COLUMN attachment_required TINYINT(1) NOT NULL DEFAULT 0 AFTER carry_forward,
            ADD COLUMN approval_workflow VARCHAR(50) NOT NULL DEFAULT 'Admin' AFTER attachment_required,
            ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER approval_workflow
        ");
        echo "Added columns to leave_types.\n";
    } catch(PDOException $e) {
        echo "Error or already exists (leave_types columns): " . $e->getMessage() . "\n";
    }

    // 3. Mark all existing leave types as inactive
    $conn->exec("UPDATE leave_types SET is_active = 0");
    echo "Marked existing leave types as inactive.\n";

    // 4. Insert new standard leave types
    $stmt = $conn->prepare("INSERT INTO leave_types (name, days_allowed, is_paid, service_period_months, gender_restriction, carry_forward, attachment_required, approval_workflow, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Casual Leave: 3 months, 6 days, Paid
    $stmt->execute(['Casual Leave', 6, 1, 3, 'All', 0, 0, 'Admin', 1]);
    
    // Earned Leave: 12 months, 10 days, Paid
    $stmt->execute(['Earned Leave', 10, 1, 12, 'All', 0, 0, 'Admin', 1]);
    
    // Medical Leave: 6 months, 30 days, Paid
    $stmt->execute(['Medical Leave', 30, 1, 6, 'All', 0, 1, 'Admin', 1]);
    
    // Maternity Leave: 6 months, 98 days, Paid, Female
    $stmt->execute(['Maternity Leave', 98, 1, 6, 'Female', 0, 1, 'Admin', 1]);
    
    // Paternity Leave: 6 months, 15 days, Paid, Male
    $stmt->execute(['Paternity Leave', 15, 1, 6, 'Male', 0, 0, 'Admin', 1]);
    
    // Leave Without Pay (LWP): 3 months, Unlimited (999), Unpaid
    $stmt->execute(['Leave Without Pay', 999, 0, 3, 'All', 0, 0, 'Admin', 1]);

    echo "Migration completed successfully.\n";
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
