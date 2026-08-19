<?php
require 'c:/wamp64/www/payrollsystem/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$conn->query("UPDATE attendance SET Status = 'Absent' WHERE Status = '' OR Status = 'Full-Day Absence'");
$conn->query("UPDATE attendance SET Status = 'Half Day' WHERE Status = 'Half-Day Absence'");
echo "Done";
?>
