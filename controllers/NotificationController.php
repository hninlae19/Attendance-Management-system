<?php
class NotificationController extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id'])) {
            $this->redirect('/payrollsystem/auth/login');
        }
    }

    public function index() {
        $notificationModel = $this->model('Notification');
        $type = $_GET['type'] ?? null;
        $notifications = $notificationModel->getAll($_SESSION['user_id'], $type, 100);
        
        $layout = $_SESSION['role'] === 'Admin' ? 'layouts/main' : 'layouts/employee';

        $this->view($layout, [
            'title' => 'Notification History',
            'content' => 'common/notifications',
            'notifications' => $notifications,
            'current_type' => $type
        ]);
    }

    public function api() {
        header('Content-Type: application/json');
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $notificationModel = $this->model('Notification');
        $action = $_GET['action'] ?? 'get';

        if ($action === 'get') {
            $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);
            $recent = $notificationModel->getAll($_SESSION['user_id'], null, 10);
            echo json_encode(['unread_count' => $unreadCount, 'notifications' => $recent]);
        } elseif ($action === 'read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;
            if ($id) {
                $notificationModel->markAsRead($id);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No ID provided']);
            }
        } elseif ($action === 'read_all' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $notificationModel->markAllAsRead($_SESSION['user_id']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
        exit;
    }
}
