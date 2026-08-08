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
            $stmt = $conn->prepare("SELECT id FROM employees WHERE department_id = :dept_id AND status = 'Active'");
            $stmt->execute([':dept_id' => $input['department_id']]);
            $employee_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } elseif (!empty($input['employee_ids'])) {
            $employee_ids = is_array($input['employee_ids']) ? $input['employee_ids'] : [$input['employee_ids']];
        } else {
            // Self (Employee)
            $stmt = $conn->prepare("SELECT id FROM employees WHERE user_id = :uid");
            $stmt->execute([':uid' => $_SESSION['user_id']]);
            $emp = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($emp) {
                $employee_ids[] = $emp['id'];
            }
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
            // Get employee name for messages if needed
            $stmt = $conn->prepare("SELECT first_name, last_name FROM employees WHERE id = ?");
            $stmt->execute([$emp_id]);
            $emp = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $emp ? $emp['first_name'] . ' ' . $emp['last_name'] : "Employee #$emp_id";

            // 1. Check Leave Requests overlapping with this date range
            $lQuery = "SELECT * FROM leave_requests 
                       WHERE employee_id = :emp_id 
                       AND status NOT IN ('Cancelled', 'Rejected') 
                       AND (start_date <= :ed AND end_date >= :sd)";
            $lStmt = $conn->prepare($lQuery);
            $lStmt->execute([':emp_id' => $emp_id, ':sd' => $start_date, ':ed' => $end_date]);
            
            if ($lStmt->rowCount() > 0) {
                $msg = "You already have a leave request on this date. Overtime cannot be requested or assigned.";
                $conflicts[] = $msg;
                continue;
            }

            // Check Overtime Requests
            $otQuery = "SELECT * FROM overtime_requests 
                        WHERE employee_id = :emp_id 
                        AND date >= :sd AND date <= :ed 
                        AND status NOT IN ('Cancelled', 'Rejected')";
            
            $otParams = [
                ':emp_id' => $emp_id, 
                ':sd' => $start_date, 
                ':ed' => $end_date
            ];

            if ($start_time && $end_time) {
                $otQuery .= " AND ((start_time <= :et AND end_time > :st) OR (start_time < :et AND end_time >= :st))";
                $otParams[':st'] = $start_time;
                $otParams[':et'] = $end_time;
            }

            $otStmt = $conn->prepare($otQuery);
            $otStmt->execute($otParams);
            
            if ($otStmt->rowCount() > 0) {
                if ($start_time && $end_time) {
                    $msg = "The selected overtime date and time are already reserved. Please choose a different schedule.";
                } else {
                    $msg = "The selected overtime date and time are already reserved. Please choose a different schedule.";
                }
                $conflicts[] = $msg;
                continue;
            }

            // Check Overtime Assignments
            $otaQuery = "SELECT a.* FROM overtime_assignments a 
                         JOIN overtime_assignment_employees e ON a.id = e.assignment_id 
                         WHERE e.employee_id = :emp_id 
                         AND a.date >= :sd AND a.date <= :ed 
                         AND a.status NOT IN ('Cancelled', 'Rejected')";
            
            $otaParams = [
                ':emp_id' => $emp_id, 
                ':sd' => $start_date, 
                ':ed' => $end_date
            ];

            if ($start_time && $end_time) {
                $otaQuery .= " AND ((a.start_time <= :et AND a.end_time > :st) OR (a.start_time < :et AND a.end_time >= :st))";
                $otaParams[':st'] = $start_time;
                $otaParams[':et'] = $end_time;
            }

            $otaStmt = $conn->prepare($otaQuery);
            $otaStmt->execute($otaParams);
            
            if ($otaStmt->rowCount() > 0) {
                $msg = "An overtime assignment already exists for the selected date and time.";
                $conflicts[] = $msg;
                continue;
            }
        }

        echo json_encode(['status' => 'success', 'has_conflict' => count($conflicts) > 0, 'messages' => array_unique($conflicts)]);
    }
}
