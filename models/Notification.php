<?php
/**
 * Notification Model (Deprecated / No-Op)
 * All real-time notifications are now computed dynamically from status columns in
 * leaverequest, overtimeassign, payroll, and employee tables with session tracking.
 */
class Notification {
    public function create($user_id, $message, $type = 'info', $link = '#', $title = 'System Notification', $sender_id = null) {
        // No-op: Notifications are purely status-driven now
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
