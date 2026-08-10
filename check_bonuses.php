<?php
require_once __DIR__ . '/config/Database.php';
$db = new Database();
$conn = $db->getConnection();
try {
    $stmt = $conn->query('SHOW COLUMNS FROM bonuses');
    if ($stmt) {
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo 'No bonuses table';
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
