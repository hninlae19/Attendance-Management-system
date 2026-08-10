<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->query("SELECT * FROM deductions WHERE type = 'Unpaid Leave' AND start_date IS NULL ORDER BY employee_id, date");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($records, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
