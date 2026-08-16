<?php
require_once __DIR__ . '/../config/database.php';

class NotificationController extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id'])) {
            $this->redirect('/payrollsystem/auth/login');
        }
    }

    public function api() {
        header('Content-Type: application/json');
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $action = $_GET['action'] ?? 'get';

        if ($action === 'get') {
            $db = new Database();
            $conn = $db->getConnection();
            $role = $_SESSION['role'] ?? '';
            $userId = $_SESSION['user_id'];
            $alerts = [];
            $idCounter = 1;

            if ($role === 'Admin') {
                // 1. Pending Leave Requests
                $stmt = $conn->query("SELECT COUNT(*) FROM leaverequest WHERE Status = 'Pending'");
                $pendingLeaves = $stmt->fetchColumn();
                if ($pendingLeaves > 0) {
                    $alerts[] = [
                        'id' => $idCounter++,
                        'title' => 'Pending Leave Requests',
                        'message' => "There are $pendingLeaves leave request(s) awaiting approval.",
                        'type' => 'warning',
                        'link' => '/admin/leaves',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // 2. Pending Overtime Assignments
                $stmt = $conn->query("SELECT COUNT(*) FROM overtimeassign WHERE Status = 'Pending' AND EmployeeResponse != 'None'");
                $pendingOT = $stmt->fetchColumn();
                if ($pendingOT > 0) {
                    $alerts[] = [
                        'id' => $idCounter++,
                        'title' => 'Overtime Responses',
                        'message' => "There are $pendingOT overtime assignment(s) requiring admin review.",
                        'type' => 'info',
                        'link' => '/admin/overtime',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // 3. Password Reset Requests
                $stmt = $conn->query("SELECT COUNT(*) FROM employee WHERE PasswordResetRequest = 1 AND Status = 'Active'");
                $pendingResets = $stmt->fetchColumn();
                if ($pendingResets > 0) {
                    $alerts[] = [
                        'id' => $idCounter++,
                        'title' => 'Password Reset Requests',
                        'message' => "There are $pendingResets employee(s) requesting a password reset.",
                        'type' => 'error',
                        'link' => '/admin/password_resets',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            } elseif ($role === 'Employee') {
                // 1. Leave Requests status changes (Only for upcoming leaves)
                $stmt = $conn->prepare("SELECT Status, StartDate FROM leaverequest WHERE EmpID = ? AND Status != 'Pending' AND StartDate >= CURDATE() ORDER BY StartDate ASC");
                $stmt->execute([$userId]);
                $upcomingLeaves = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($upcomingLeaves as $leave) {
                    $alerts[] = [
                        'id' => $idCounter++,
                        'title' => 'Leave Request ' . $leave['Status'],
                        'message' => "Your upcoming leave starting on {$leave['StartDate']} has been {$leave['Status']}.",
                        'type' => $leave['Status'] === 'Approved' ? 'success' : 'error',
                        'link' => '/employee/leaves',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // 2. Overtime Assignments (Pending user response)
                $stmt = $conn->prepare("SELECT OvertimeDate FROM overtimeassign WHERE EmpID = ? AND Status = 'Pending' AND EmployeeResponse = 'None'");
                $stmt->execute([$userId]);
                $pendingOT = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($pendingOT as $ot) {
                    $alerts[] = [
                        'id' => $idCounter++,
                        'title' => 'New Overtime Assignment',
                        'message' => "You have been assigned overtime on {$ot['OvertimeDate']}. Please review and respond.",
                        'type' => 'info',
                        'link' => '/employee/overtime',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // 3. Payroll (Only for the current month)
                $currentMonthStr = date('F Y');
                $stmt = $conn->prepare("SELECT PayrollMonth, Status FROM payroll WHERE EmpID = ? AND PayrollMonth = ?");
                $stmt->execute([$userId, $currentMonthStr]);
                $currentPayroll = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($currentPayroll) {
                    $alerts[] = [
                        'id' => $idCounter++,
                        'title' => 'Salary Update',
                        'message' => "Your salary for {$currentPayroll['PayrollMonth']} is currently {$currentPayroll['Status']}.",
                        'type' => $currentPayroll['Status'] === 'Paid' ? 'success' : 'warning',
                        'link' => '/employee/payroll',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }

            echo json_encode(['unread_count' => count($alerts), 'notifications' => $alerts]);
        } else {
            echo json_encode(['success' => true]);
        }
        exit;
    }
}
