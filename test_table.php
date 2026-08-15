<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
try {
    $stmt = $conn->query("SHOW TABLES LIKE 'notifications'");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo $e->getMessage();
}
