<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Add missing columns
    $queries = [
        "ALTER TABLE deductions ADD COLUMN type VARCHAR(50) NOT NULL DEFAULT 'Other'",
        "ALTER TABLE deductions ADD COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Active'",
        "ALTER TABLE deductions ADD COLUMN notes TEXT NULL",
        "ALTER TABLE deductions ADD COLUMN created_by VARCHAR(50) NULL",
        "ALTER TABLE deductions ADD COLUMN start_date DATE NULL",
        "ALTER TABLE deductions ADD COLUMN end_date DATE NULL",
        "ALTER TABLE deductions ADD COLUMN total_absent_days INT NULL",
        "ALTER TABLE deductions ADD COLUMN source VARCHAR(100) NULL",
        "ALTER TABLE deductions ADD COLUMN related_id INT NULL"
    ];

    foreach ($queries as $q) {
        try {
            $conn->exec($q);
            echo "Successfully executed: $q\n";
        } catch (PDOException $e) {
            echo "Error executing $q: " . $e->getMessage() . "\n";
        }
    }
    
    echo "Database update completed.\n";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
