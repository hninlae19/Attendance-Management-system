<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$conn->query('DROP TABLE IF EXISTS notifications');
echo 'Notifications table dropped successfully.';
