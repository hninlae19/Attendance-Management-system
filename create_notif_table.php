<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS `notifications` (
      `id` int NOT NULL AUTO_INCREMENT,
      `user_id` int NOT NULL,
      `title` varchar(255) NOT NULL,
      `sender_id` int DEFAULT NULL,
      `message` text NOT NULL,
      `type` varchar(50) DEFAULT 'info',
      `link` varchar(255) DEFAULT '#',
      `is_read` tinyint(1) DEFAULT 0,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Table created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
