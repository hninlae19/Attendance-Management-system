<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->query("
        SELECT d.*, e.first_name, e.last_name 
        FROM deductions d 
        JOIN employees e ON d.employee_id = e.id 
        WHERE e.first_name LIKE '%myat%' 
        ORDER BY d.id DESC LIMIT 10
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
