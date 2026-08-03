<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();

try {
    $conn->exec("ALTER TABLE notifications ADD COLUMN title VARCHAR(255) DEFAULT 'System Notification'");
    echo "Added title column.<br>";
} catch(Exception $e) {
    echo "Title exists or error: " . $e->getMessage() . "<br>";
}

try {
    $conn->exec("ALTER TABLE notifications ADD COLUMN sender_id INT DEFAULT NULL");
    echo "Added sender_id column.<br>";
} catch(Exception $e) {
    echo "Sender_id exists or error: " . $e->getMessage() . "<br>";
}

echo "Done.";
