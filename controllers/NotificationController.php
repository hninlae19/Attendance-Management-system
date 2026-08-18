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

        // Initialize session array to track notified records during current login session
        if (!isset($_SESSION['notified_items']) || !is_array($_SESSION['notified_items'])) {
            $_SESSION['notified_items'] = [];
        }

        $action = $_GET['action'] ?? 'get';

        if ($action === 'get') {
            $db = new Database();
            $conn = $db->getConnection();
            $role = $_SESSION['role'] ?? '';
            $userId = $_SESSION['user_id'];
            $alerts = [];
            $newAlertsCount = 0;

            if ($role === 'Admin') {
                // 1. Pending Leave Requests (Admin view)
                $stmt = $conn->query("SELECT RequestID, EmpID, StartDate FROM leaverequest WHERE Status = 'Pending'");
                $pendingLeaves = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($pendingLeaves as $leave) {
                    $key = "admin_leave_" . $leave['RequestID'];
                    $isNew = !in_array($key, $_SESSION['notified_items']);
                    if ($isNew) {
                        $_SESSION['notified_items'][] = $key;
                        $newAlertsCount++;
                    }
                    $alerts[] = [
                        'id' => $key,
                        'title' => 'Pending Leave Request',
                        'message' => "Leave request for " . $leave['StartDate'] . " requires approval.",
                        'type' => 'warning',
                        'link' => '/admin/leaves',
                        'is_new' => $isNew,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // 2. Pending Overtime Assignments (Admin view)
                $stmt = $conn->query("SELECT OvertimeID, OvertimeDate FROM overtimeassign WHERE Status = 'Pending' AND EmployeeResponse != 'None'");
                $pendingOT = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($pendingOT as $ot) {
                    $key = "admin_ot_" . $ot['OvertimeID'];
                    $isNew = !in_array($key, $_SESSION['notified_items']);
                    if ($isNew) {
                        $_SESSION['notified_items'][] = $key;
                        $newAlertsCount++;
                    }
                    $alerts[] = [
                        'id' => $key,
                        'title' => 'Overtime Response',
                        'message' => "Overtime response for " . $ot['OvertimeDate'] . " is ready for review.",
                        'type' => 'info',
                        'link' => '/admin/overtime_assignments',
                        'is_new' => $isNew,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // 3. Password Reset Requests
                $stmt = $conn->query("SELECT EmpID, FirstName, LastName FROM employee WHERE PasswordResetRequest = 1 AND Status = 'Active'");
                $pendingResets = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($pendingResets as $emp) {
                    $key = "admin_reset_" . $emp['EmpID'];
                    $isNew = !in_array($key, $_SESSION['notified_items']);
                    if ($isNew) {
                        $_SESSION['notified_items'][] = $key;
                        $newAlertsCount++;
                    }
                    $alerts[] = [
                        'id' => $key,
                        'title' => 'Password Reset Request',
                        'message' => $emp['FirstName'] . " " . $emp['LastName'] . " requested a password reset.",
                        'type' => 'error',
                        'link' => '/admin/password_resets',
                        'is_new' => $isNew,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

            } elseif ($role === 'Employee') {
                // =========================================================
                // 1. LEAVE REQUESTS: Trigger on 'Approved' or 'Rejected'
                // =========================================================
                $stmt = $conn->prepare("SELECT RequestID, Status, StartDate, EndDate FROM leaverequest WHERE EmpID = ? AND Status IN ('Approved', 'Rejected') ORDER BY RequestID DESC");
                $stmt->execute([$userId]);
                $leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($leaves as $leave) {
                    $itemKey = "leave_" . $leave['RequestID'] . "_" . strtolower($leave['Status']);
                    $isNew = !in_array($itemKey, $_SESSION['notified_items']);
                    
                    if ($isNew) {
                        $_SESSION['notified_items'][] = $itemKey;
                        $newAlertsCount++;
                    }

                    $alerts[] = [
                        'id' => $itemKey,
                        'title' => 'Leave Request ' . $leave['Status'],
                        'message' => "Your leave request for {$leave['StartDate']} to {$leave['EndDate']} has been {$leave['Status']}.",
                        'type' => $leave['Status'] === 'Approved' ? 'success' : 'error',
                        'link' => '/employee/leaves',
                        'is_new' => $isNew,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // =========================================================
                // 2. OVERTIME ASSIGNMENTS: Trigger on Pending or Approved
                // =========================================================
                $stmt = $conn->prepare("SELECT OvertimeID, OvertimeDate, Status, EmployeeResponse FROM overtimeassign WHERE EmpID = ? AND (Status = 'Pending' AND EmployeeResponse = 'None' OR Status IN ('Approved', 'Accepted')) ORDER BY OvertimeID DESC");
                $stmt->execute([$userId]);
                $overtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($overtimes as $ot) {
                    $itemKey = "ot_" . $ot['OvertimeID'] . "_" . strtolower($ot['Status']);
                    $isNew = !in_array($itemKey, $_SESSION['notified_items']);
                    
                    if ($isNew) {
                        $_SESSION['notified_items'][] = $itemKey;
                        $newAlertsCount++;
                    }

                    $alerts[] = [
                        'id' => $itemKey,
                        'title' => 'Overtime Assignment',
                        'message' => "Overtime scheduled on {$ot['OvertimeDate']} is {$ot['Status']}.",
                        'type' => 'info',
                        'link' => '/employee/overtime',
                        'is_new' => $isNew,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }

                // =========================================================
                // 3. PAYROLL: Trigger on 'Approved' or 'Paid'
                // =========================================================
                $stmt = $conn->prepare("SELECT PayrollID, PayrollMonth, Status, NetSalary FROM payroll WHERE EmpID = ? AND Status IN ('Approved', 'Paid') ORDER BY PayrollID DESC");
                $stmt->execute([$userId]);
                $payrolls = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($payrolls as $pr) {
                    $itemKey = "payroll_" . $pr['PayrollID'] . "_" . strtolower($pr['Status']);
                    $isNew = !in_array($itemKey, $_SESSION['notified_items']);
                    
                    if ($isNew) {
                        $_SESSION['notified_items'][] = $itemKey;
                        $newAlertsCount++;
                    }

                    $alerts[] = [
                        'id' => $itemKey,
                        'title' => 'Salary Update',
                        'message' => "Your salary for {$pr['PayrollMonth']} has been {$pr['Status']}.",
                        'type' => $pr['Status'] === 'Paid' ? 'success' : 'warning',
                        'link' => '/employee/salary_history',
                        'is_new' => $isNew,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }

            echo json_encode([
                'unread_count' => count($alerts),
                'new_count' => $newAlertsCount,
                'notifications' => $alerts
            ]);
        } else {
            echo json_encode(['success' => true]);
        }
        exit;
    }
}

