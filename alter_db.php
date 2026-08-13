<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "ALTER TABLE attendance MODIFY COLUMN status enum('Present','Late','Half Day','Absent','Paid Leave','Unpaid Leave','Holiday','N/A') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Absent'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    echo "Attendance status ENUM successfully updated.\n";
} catch (Exception $e) {
    echo "Error updating ENUM: " . $e->getMessage() . "\n";
}
