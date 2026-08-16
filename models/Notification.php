<?php
class Notification {
    private $conn;
    private $table = 'notifications';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($user_id, $message, $type = 'info', $link = '#', $title = 'System Notification', $sender_id = null) {
        return true;
    }

    public function getUnreadCount($user_id) {
        return 0;
    }

    public function getAll($user_id, $type = null, $limit = 50) {
        return [];
    }

    public function markAsRead($id) {
        return true;
    }

    public function markAllAsRead($user_id) {
        return true;
    }
}

