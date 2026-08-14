<?php

class ApiController extends Controller {
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function validate_conflict() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        
        $employee_ids = [];
        
        $db = new Database();
        $conn = $db->getConnection();
        
        if (isset($input['assign_type']) && $input['assign_type'] === 'department' && !empty($input['department_id'])) {
            $stmt = $conn->prepare("SELECT e.EmpID FROM employee e JOIN position p ON e.PositionID = p.PositionID WHERE p.DeptID = :dept_id AND e.Status = 'Active'");
            $stmt->execute([':dept_id' => $input['department_id']]);
            $employee_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } elseif (!empty($input['employee_ids'])) {
            $employee_ids = is_array($input['employee_ids']) ? $input['employee_ids'] : [$input['employee_ids']];
        } else {
            // Self (Employee)
            $employee_ids[] = $_SESSION['employee_id'];
        }
        
        if (empty($employee_ids)) {
            echo json_encode(['status' => 'success', 'conflicts' => []]);
            return;
        }

        $start_date = $input['start_date'] ?? $input['date'] ?? null;
        $end_date = $input['end_date'] ?? $start_date;
        $start_time = $input['start_time'] ?? null;
        $end_time = $input['end_time'] ?? null;
        
        if (!$start_date) {
            echo json_encode(['status' => 'error', 'message' => 'Missing date']);
            return;
        }

        $conflicts = [];
        
        foreach ($employee_ids as $emp_id) {
            // 1. Check Leave Requests overlapping with this date range
            $lQuery = "SELECT * FROM leaverequest 
                       WHERE EmpID = :emp_id 
                       AND Status NOT IN ('Cancelled', 'Rejected') 
                       AND (StartDate <= :ed AND EndDate >= :sd)";
            $lStmt = $conn->prepare($lQuery);
            $lStmt->execute([':emp_id' => $emp_id, ':sd' => $start_date, ':ed' => $end_date]);
            
            if ($lStmt->rowCount() > 0) {
                $msg = "You already have an active leave request covering this date. Overtime or additional leaves cannot be requested.";
                $conflicts[] = $msg;
                continue;
            }

            // 2. Check Attendance overlapping with this date range
            $aQuery = "SELECT * FROM attendance 
                       WHERE EmpID = :emp_id 
                       AND AttendanceDate >= :sd 
                       AND AttendanceDate <= :ed
                       AND CheckInTime IS NOT NULL";
            $aStmt = $conn->prepare($aQuery);
            $aStmt->execute([':emp_id' => $emp_id, ':sd' => $start_date, ':ed' => $end_date]);
            
            if ($aStmt->rowCount() > 0) {
                $msg = "Leave request failed: Leave cannot be requested for a date with an existing check-in record.";
                $conflicts[] = $msg;
                continue;
            }
        }

        echo json_encode(['status' => 'success', 'has_conflict' => count($conflicts) > 0, 'messages' => array_unique($conflicts)]);
    }
}
