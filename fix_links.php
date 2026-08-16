<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$conn->exec("UPDATE notifications SET link = REPLACE(link, '/payrollsystem', '')");
echo "Updated links";
