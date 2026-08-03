<?php
require_once __DIR__ . '/core/Database.php';
$db = new Database();
$conn = $db->getConnection();
try {
    $conn->exec("ALTER TABLE notifications ADD COLUMN title VARCHAR(255) DEFAULT 'System Notification' AFTER user_id");
    echo "Added title\n";
} catch (Exception $e) { echo $e->getMessage() . "\n"; }
try {
    $conn->exec("ALTER TABLE notifications ADD COLUMN sender_id INT NULL AFTER title");
    echo "Added sender_id\n";
} catch (Exception $e) { echo $e->getMessage() . "\n"; }
echo "Done";
