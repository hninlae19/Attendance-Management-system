<?php
class Notification {
    private $conn;
    private $table = 'notifications';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($user_id, $message, $type = 'info', $link = '#', $title = 'System Notification', $sender_id = null) {
        $query = "INSERT INTO " . $this->table . " SET user_id=:user_id, title=:title, sender_id=:sender_id, message=:message, type=:type, link=:link, is_read=0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':link', $link);
        return $stmt->execute();
    }

    public function getUnreadCount($user_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE user_id=:user_id AND is_read=0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }

    public function getAll($user_id, $type = null, $limit = 50) {
        $query = "SELECT n.*, u.email as sender_email, e.first_name as sender_name
                  FROM " . $this->table . " n
                  LEFT JOIN users u ON n.sender_id = u.id
                  LEFT JOIN employees e ON n.sender_id = e.user_id
                  WHERE n.user_id=:user_id";
        
        if ($type) {
            $query .= " AND n.type = :type";
        }
        $query .= " ORDER BY n.created_at DESC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        if ($type) {
            $stmt->bindParam(':type', $type);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($id) {
        $query = "UPDATE " . $this->table . " SET is_read=1 WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function markAllAsRead($user_id) {
        $query = "UPDATE " . $this->table . " SET is_read=1 WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}

