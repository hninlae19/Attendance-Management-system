<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $tables = [];
    $stmt = $conn->query("SHOW TABLES");
    while($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tableName = $row[0];
        $colStmt = $conn->query("SHOW COLUMNS FROM " . $tableName);
        $columns = $colStmt->fetchAll(PDO::FETCH_ASSOC);
        $tables[$tableName] = $columns;
    }
    header('Content-Type: application/json');
    echo json_encode($tables, JSON_PRETTY_PRINT);
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
