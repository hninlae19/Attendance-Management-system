<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM leaverequest");
$stmt->execute();
var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
