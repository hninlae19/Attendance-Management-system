<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
print_r($conn->query('SELECT * FROM settings')->fetch(PDO::FETCH_ASSOC));
