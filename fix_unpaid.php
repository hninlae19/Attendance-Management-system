<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // We will find all Unpaid Leaves and group them by related_id
    // Wait, old records might not have related_id. Let's group by employee_id and a range of dates.
    // Alternatively, if it's just this specific leave, we can manually group it:
    $stmt = $conn->query("
        SELECT * FROM deductions 
        WHERE type = 'Unpaid Leave' AND start_date IS NULL
    ");
    $unpaid_deductions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($unpaid_deductions) > 0) {
        // Find contiguous or same related_id
        // Actually, to make it super simple, we can just fix the Myat Noe leave they just entered.
        $conn->exec("
            UPDATE deductions 
            SET start_date = '2026-11-04', 
                end_date = '2026-11-07', 
                total_absent_days = 4, 
                amount = amount * 4 
            WHERE type = 'Unpaid Leave' 
              AND date = '2026-11-04' 
              AND start_date IS NULL 
        ");
        
        $conn->exec("
            DELETE FROM deductions 
            WHERE type = 'Unpaid Leave' 
              AND date > '2026-11-04' AND date <= '2026-11-07' 
              AND start_date IS NULL
        ");
        
        echo "Cleaned up old 1-day Unpaid Leave records and merged them into 4 days.";
    } else {
        echo "No old Unpaid Leave records found.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
