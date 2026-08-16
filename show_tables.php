<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query('SHOW TABLES');
foreach($stmt->fetchAll(PDO::FETCH_NUM) as $row) {
    echo $row[0] . "\n";
}
