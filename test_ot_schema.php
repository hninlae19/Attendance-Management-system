<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
try {
    $stmt = $conn->query('DESCRIBE overtimeassign');
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
