<?php
require_once __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    $conn->beginTransaction();

    // 1. Add late_deduction_rate to settings table
    $stmt1 = $conn->prepare("ALTER TABLE settings ADD COLUMN late_deduction_rate DECIMAL(5,2) NOT NULL DEFAULT 0.00 AFTER late_time");
    $stmt1->execute();
    echo "Added late_deduction_rate to settings table.\n";

    // 2. Update attendance status ENUM
    // First, modify the column to include new values
    $stmt2 = $conn->prepare("ALTER TABLE attendance MODIFY COLUMN status ENUM('Present','Late','Half Day','Absent','Paid Leave','Unpaid Leave','Holiday') NOT NULL DEFAULT 'Absent'");
    $stmt2->execute();
    echo "Updated attendance status ENUM.\n";

    $conn->commit();
    echo "Migration completed successfully.\n";
} catch (Exception $e) {
    $conn->rollBack();
    echo "Migration failed: " . $e->getMessage() . "\n";
}
