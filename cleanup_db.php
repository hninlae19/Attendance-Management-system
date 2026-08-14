<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$conn->exec("UPDATE leaverequest SET Status = 'Approved' WHERE Status = 'approve'");
$conn->exec("UPDATE leaverequest SET Status = 'Rejected' WHERE Status = 'reject'");
echo "Cleaned up statuses.";
?>
