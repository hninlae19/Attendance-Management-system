<?php
class AdminController extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
            $this->redirect('/payrollsystem/auth/login');
        }
    }

    public function index() {
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        $leaveRequestModel = $this->model('LeaveRequest');
        $overtimeRequestModel = $this->model('OvertimeRequest');
        $payrollModel = $this->model('Payroll');

        $totalEmployees = count($employeeModel->getAll());
        
        $today = date('Y-m-d');
        $attendanceToday = $attendanceModel->getAll($today);
        $presentToday = 0;
        $lateToday = 0;
        foreach ($attendanceToday as $att) {
            if ($att['status'] !== 'Absent') {
                $presentToday++;
            }
            if ($att['status'] === 'Late') {
                $lateToday++;
            }
        }

        $db = new Database();
        $conn = $db->getConnection();
        $leaveQuery = "SELECT count(id) as count FROM leave_requests WHERE status='Approved' AND start_date <= :today AND end_date >= :today";
        $leaveStmt = $conn->prepare($leaveQuery);
        $leaveStmt->bindParam(':today', $today);
        $leaveStmt->execute();
        $employeesOnLeave = $leaveStmt->fetch(PDO::FETCH_ASSOC)['count'];

        $absentToday = $totalEmployees - $presentToday - $employeesOnLeave;
        if ($absentToday < 0) $absentToday = 0;

        $pendingLeaves = count(array_filter($leaveRequestModel->getAll(), function($l) { return $l['status'] == 'Pending'; }));
        $pendingOvertime = count(array_filter($overtimeRequestModel->getAll(), function($o) { return $o['status'] == 'Pending'; }));

        $activeEmployees = count(array_filter($employeeModel->getAll(), function($e) { return $e['status'] == 'Active'; }));

        $month = date('n');
        $year = date('Y');
        $payrollQuery = "SELECT SUM(net_salary) as total FROM payroll WHERE month = :m AND year = :y";
        $payStmt = $conn->prepare($payrollQuery);
        $payStmt->bindParam(':m', $month);
        $payStmt->bindParam(':y', $year);
        $payStmt->execute();
        $monthlyPayroll = $payStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $bonusQuery = "SELECT SUM(amount) as total FROM bonuses WHERE MONTH(date) = :m AND YEAR(date) = :y";
        $bonusStmt = $conn->prepare($bonusQuery);
        $bonusStmt->bindParam(':m', $month);
        $bonusStmt->bindParam(':y', $year);
        $bonusStmt->execute();
        $monthlyBonus = $bonusStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $otCostQuery = "SELECT SUM(ot_amount) as total FROM payroll WHERE month = :m AND year = :y";
        $otStmt = $conn->prepare($otCostQuery);
        $otStmt->bindParam(':m', $month);
        $otStmt->bindParam(':y', $year);
        $otStmt->execute();
        $monthlyOvertimeCost = $otStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $approvedLeaves = count(array_filter($leaveRequestModel->getAll(), function($l) { return $l['status'] == 'Approved'; }));
        $rejectedLeaves = count(array_filter($leaveRequestModel->getAll(), function($l) { return $l['status'] == 'Rejected'; }));

        $deductionQuery = "SELECT SUM(amount) as total FROM deductions WHERE MONTH(date) = :m AND YEAR(date) = :y";
        $deductionStmt = $conn->prepare($deductionQuery);
        $deductionStmt->bindParam(':m', $month);
        $deductionStmt->bindParam(':y', $year);
        $deductionStmt->execute();
        $monthlyDeduction = $deductionStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $recentAttendance = array_slice($attendanceToday, 0, 5);

        $this->view('layouts/main', [
            'title' => 'Admin Dashboard',
            'content' => 'admin/dashboard',
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'presentToday' => $presentToday,
            'lateToday' => $lateToday,
            'absentToday' => $absentToday,
            'employeesOnLeave' => $employeesOnLeave,
            'pendingLeaves' => $pendingLeaves,
            'approvedLeaves' => $approvedLeaves,
            'rejectedLeaves' => $rejectedLeaves,
            'pendingOvertime' => $pendingOvertime,
            'monthlyPayroll' => $monthlyPayroll,
            'monthlyBonus' => $monthlyBonus,
            'monthlyOvertimeCost' => $monthlyOvertimeCost,
            'monthlyDeduction' => $monthlyDeduction,
            'recentAttendance' => $recentAttendance
        ]);
    }

    public function dashboardApi() {
        header('Content-Type: application/json');
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        $leaveRequestModel = $this->model('LeaveRequest');
        $overtimeRequestModel = $this->model('OvertimeRequest');

        $totalEmployees = count($employeeModel->getAll());
        
        $today = date('Y-m-d');
        $attendanceToday = $attendanceModel->getAll($today);
        $presentToday = 0;
        $lateToday = 0;
        foreach ($attendanceToday as $att) {
            if ($att['status'] !== 'Absent') {
                $presentToday++;
            }
            if ($att['status'] === 'Late') {
                $lateToday++;
            }
        }

        $db = new Database();
        $conn = $db->getConnection();
        $leaveQuery = "SELECT count(id) as count FROM leave_requests WHERE status='Approved' AND start_date <= :today AND end_date >= :today";
        $leaveStmt = $conn->prepare($leaveQuery);
        $leaveStmt->bindParam(':today', $today);
        $leaveStmt->execute();
        $employeesOnLeave = $leaveStmt->fetch(PDO::FETCH_ASSOC)['count'];

        $absentToday = $totalEmployees - $presentToday - $employeesOnLeave;
        if ($absentToday < 0) $absentToday = 0;

        $pendingLeaves = count(array_filter($leaveRequestModel->getAll(), function($l) { return $l['status'] == 'Pending'; }));
        $pendingOvertime = count(array_filter($overtimeRequestModel->getAll(), function($o) { return $o['status'] == 'Pending'; }));

        $activeEmployees = count(array_filter($employeeModel->getAll(), function($e) { return $e['status'] == 'Active'; }));
        
        $month = date('n');
        $year = date('Y');
        $payrollQuery = "SELECT SUM(net_salary) as total FROM payroll WHERE month = :m AND year = :y";
        $payStmt = $conn->prepare($payrollQuery);
        $payStmt->bindParam(':m', $month);
        $payStmt->bindParam(':y', $year);
        $payStmt->execute();
        $monthlyPayroll = $payStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $bonusQuery = "SELECT SUM(amount) as total FROM bonuses WHERE MONTH(date) = :m AND YEAR(date) = :y";
        $bonusStmt = $conn->prepare($bonusQuery);
        $bonusStmt->bindParam(':m', $month);
        $bonusStmt->bindParam(':y', $year);
        $bonusStmt->execute();
        $monthlyBonus = $bonusStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $deductionQuery = "SELECT SUM(amount) as total FROM deductions WHERE MONTH(date) = :m AND YEAR(date) = :y";
        $deductionStmt = $conn->prepare($deductionQuery);
        $deductionStmt->bindParam(':m', $month);
        $deductionStmt->bindParam(':y', $year);
        $deductionStmt->execute();
        $monthlyDeduction = $deductionStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $recentAttendance = array_slice($attendanceToday, 0, 5);

        echo json_encode([
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'presentToday' => $presentToday,
            'lateToday' => $lateToday,
            'absentToday' => $absentToday,
            'employeesOnLeave' => $employeesOnLeave,
            'pendingLeaves' => $pendingLeaves,
            'pendingOvertime' => $pendingOvertime,
            'monthlyPayroll' => $monthlyPayroll,
            'monthlyBonus' => $monthlyBonus,
            'monthlyDeduction' => $monthlyDeduction,
            'recentAttendance' => $recentAttendance
        ]);
        exit;
    }

    public function payrollTrendApi() {
        header('Content-Type: application/json');
        $db = new Database();
        $conn = $db->getConnection();
        
        $labels = [];
        $payrollData = [];
        $bonusData = [];
        $deductionData = [];
        
        // Fetch last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $monthStr = date('Y-m', strtotime("-$i months"));
            $m = date('n', strtotime($monthStr));
            $y = date('Y', strtotime($monthStr));
            
            $labels[] = date('M Y', strtotime($monthStr));
            
            // Payroll
            $stmt = $conn->prepare("SELECT SUM(net_salary) as total FROM payroll WHERE month = :m AND year = :y");
            $stmt->execute([':m' => $m, ':y' => $y]);
            $payrollData[] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Bonuses
            $stmt = $conn->prepare("SELECT SUM(amount) as total FROM bonuses WHERE MONTH(date) = :m AND YEAR(date) = :y");
            $stmt->execute([':m' => $m, ':y' => $y]);
            $bonusData[] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Deductions
            $stmt = $conn->prepare("SELECT SUM(amount) as total FROM deductions WHERE MONTH(date) = :m AND YEAR(date) = :y");
            $stmt->execute([':m' => $m, ':y' => $y]);
            $deductionData[] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        }
        
        echo json_encode([
            'labels' => $labels,
            'payroll' => $payrollData,
            'bonus' => $bonusData,
            'deduction' => $deductionData
        ]);
        exit;
    }

    public function departments() {
        $departmentModel = $this->model('Department');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    $departmentModel->name = $_POST['name'];
                    $departmentModel->create();
                } elseif ($_POST['action'] === 'edit') {
                    $departmentModel->id = $_POST['id'];
                    $departmentModel->name = $_POST['name'];
                    $departmentModel->update();
                } elseif ($_POST['action'] === 'delete') {
                    $departmentModel->id = $_POST['id'];
                    $departmentModel->delete();
                }
            }
            $this->redirect('/payrollsystem/admin/departments');
        }

        $departments = $departmentModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Departments',
            'content' => 'admin/departments',
            'departments' => $departments
        ]);
    }
    
    public function positions() {
        $positionModel = $this->model('Position');
        $departmentModel = $this->model('Department');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    $positionModel->name = $_POST['name'];
                    $positionModel->department_id = $_POST['department_id'];
                    $positionModel->basic_salary = $_POST['basic_salary'] ?? 0;
                    $positionModel->create();
                } elseif ($_POST['action'] === 'edit') {
                    $positionModel->id = $_POST['id'];
                    $positionModel->name = $_POST['name'];
                    $positionModel->department_id = $_POST['department_id'];
                    $positionModel->basic_salary = $_POST['basic_salary'] ?? 0;
                    $positionModel->update();
                } elseif ($_POST['action'] === 'delete') {
                    $positionModel->id = $_POST['id'];
                    $positionModel->delete();
                }
            }
            $this->redirect('/payrollsystem/admin/positions');
        }

        $positions = $positionModel->getAll();
        $departments = $departmentModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Positions',
            'content' => 'admin/positions',
            'positions' => $positions,
            'departments' => $departments
        ]);
    }
    
    public function employees() {
        $employeeModel = $this->model('Employee');
        $departmentModel = $this->model('Department');
        $positionModel = $this->model('Position');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    $employeeModel->employee_code = $_POST['employee_code'];
                    $employeeModel->first_name = $_POST['first_name'];
                    $employeeModel->last_name = $_POST['last_name'];
                    $employeeModel->gender = $_POST['gender'] ?? 'Other';
                    $employeeModel->department_id = $_POST['department_id'];
                    $employeeModel->position_id = $_POST['position_id'];
                    $employeeModel->basic_salary = $_POST['basic_salary'];
                    $employeeModel->join_date = $_POST['join_date'];
                    $employeeModel->phone = $_POST['phone'];
                    $employeeModel->address = $_POST['address'];
                    
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $result = $employeeModel->create($email, $password);
                    if($result !== true) {
                        // handle error appropriately in real app (e.g. flash message)
                    }
                } elseif ($_POST['action'] === 'delete') {
                    $employeeModel->id = $_POST['id'];
                    $employeeModel->delete();
                }
            }
            $this->redirect('/payrollsystem/admin/employees');
        }

        $employees = $employeeModel->getAll();
        $departments = $departmentModel->getAll();
        $positions = $positionModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Employees',
            'content' => 'admin/employees',
            'employees' => $employees,
            'departments' => $departments,
            'positions' => $positions
        ]);
    }

    public function employee($id) {
        $employeeModel = $this->model('Employee');
        $departmentModel = $this->model('Department');
        $positionModel = $this->model('Position');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
            $employeeModel->id = $id;
            $employeeModel->first_name = $_POST['first_name'];
            $employeeModel->last_name = $_POST['last_name'];
            $employeeModel->gender = $_POST['gender'] ?? 'Other';
            $employeeModel->department_id = $_POST['department_id'];
            $employeeModel->position_id = $_POST['position_id'];
            $employeeModel->basic_salary = $_POST['basic_salary'];
            $employeeModel->join_date = $_POST['join_date'];
            $employeeModel->phone = $_POST['phone'];
            $employeeModel->address = $_POST['address'];
            if (isset($_POST['status'])) {
                $employeeModel->status = $_POST['status'];
            }
            
            $employeeModel->update();
            $this->redirect('/payrollsystem/admin/employee/' . $id);
        }

        $employee = $employeeModel->getById($id);
        
        if(!$employee) {
            $this->redirect('/payrollsystem/admin/employees');
        }

        $departments = $departmentModel->getAll();
        $positions = $positionModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Employee Details',
            'content' => 'admin/employee_details',
            'employee' => $employee,
            'departments' => $departments,
            'positions' => $positions
        ]);
    }

    public function attendance() {
        $departmentModel = $this->model('Department');
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
            $attendanceModel->handleCorrection($_POST['id'], $_POST['action']);
            $this->redirect('/payrollsystem/admin/attendance');
        }

        $departments = $departmentModel->getAll();
        $employees = $employeeModel->getAll();
        $corrections = $attendanceModel->getCorrections();

        $this->view('layouts/main', [
            'title' => 'Attendance Management',
            'content' => 'admin/attendance',
            'departments' => $departments,
            'employees' => $employees,
            'corrections' => $corrections
        ]);
    }

    public function attendanceApi() {
        header('Content-Type: application/json');
        
        $attendanceModel = $this->model('Attendance');

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $filters = [
            'date_start' => $_GET['date_start'] ?? '',
            'date_end' => $_GET['date_end'] ?? '',
            'department_id' => $_GET['department_id'] ?? '',
            'employee_id' => $_GET['employee_id'] ?? '',
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        // Handle view_type preset (Daily/Weekly/Monthly) if provided instead of exact dates
        if (empty($filters['date_start']) && empty($filters['date_end']) && isset($_GET['view_type'])) {
            $view_type = $_GET['view_type'];
            if ($view_type === 'daily') {
                $filters['date_start'] = date('Y-m-d');
                $filters['date_end'] = date('Y-m-d');
            } elseif ($view_type === 'weekly') {
                $filters['date_start'] = date('Y-m-d', strtotime('monday this week'));
                $filters['date_end'] = date('Y-m-d', strtotime('sunday this week'));
            } elseif ($view_type === 'monthly') {
                $filters['date_start'] = date('Y-m-01');
                $filters['date_end'] = date('Y-m-t');
            }
        }

        $total = $attendanceModel->getTotalAttendanceCount($filters);
        $data = $attendanceModel->getPaginatedAttendance($filters, $limit, $offset);

        echo json_encode([
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ]);
        exit;
    }



    public function leaves() {
        $leaveRequestModel = $this->model('LeaveRequest');
        $departmentModel = $this->model('Department');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
            $remark = $_POST['admin_remark'] ?? '';
            $leaveRequestModel->handleRequest($_POST['id'], $_POST['action'], $remark);
            $this->redirect('/payrollsystem/admin/leaves');
        }

        $filters = [
            'department_id' => $_GET['department_id'] ?? '',
            'search' => $_GET['search'] ?? '',
            'date' => $_GET['date'] ?? ''
        ];
        
        $limit = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $total = $leaveRequestModel->getTotalCount($filters);
        $leaveRequests = $leaveRequestModel->getFilteredRequests($filters, $limit, $offset);
        $departments = $departmentModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Leave Management',
            'content' => 'admin/leaves',
            'leaveRequests' => $leaveRequests,
            'departments' => $departments,
            'filters' => $filters,
            'page' => $page,
            'total_pages' => ceil($total / $limit)
        ]);
    }

    public function leave_types() {
        $leaveTypeModel = $this->model('LeaveType');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    $leaveTypeModel->name = $_POST['name'];
                    $leaveTypeModel->days_allowed = $_POST['days_allowed'];
                    $leaveTypeModel->is_paid = isset($_POST['is_paid']) ? 1 : 0;
                    $leaveTypeModel->service_period_months = $_POST['service_period_months'] ?? 0;
                    $leaveTypeModel->gender_restriction = $_POST['gender_restriction'] ?? 'All';
                    $leaveTypeModel->carry_forward = isset($_POST['carry_forward']) ? 1 : 0;
                    $leaveTypeModel->attachment_required = isset($_POST['attachment_required']) ? 1 : 0;
                    $leaveTypeModel->approval_workflow = $_POST['approval_workflow'] ?? 'Admin';
                    $leaveTypeModel->is_active = isset($_POST['is_active']) ? 1 : 0;
                    $leaveTypeModel->create();
                } elseif ($_POST['action'] === 'edit') {
                    $leaveTypeModel->id = $_POST['id'];
                    $leaveTypeModel->name = $_POST['name'];
                    $leaveTypeModel->days_allowed = $_POST['days_allowed'];
                    $leaveTypeModel->is_paid = isset($_POST['is_paid']) ? 1 : 0;
                    $leaveTypeModel->service_period_months = $_POST['service_period_months'] ?? 0;
                    $leaveTypeModel->gender_restriction = $_POST['gender_restriction'] ?? 'All';
                    $leaveTypeModel->carry_forward = isset($_POST['carry_forward']) ? 1 : 0;
                    $leaveTypeModel->attachment_required = isset($_POST['attachment_required']) ? 1 : 0;
                    $leaveTypeModel->approval_workflow = $_POST['approval_workflow'] ?? 'Admin';
                    $leaveTypeModel->is_active = isset($_POST['is_active']) ? 1 : 0;
                    $leaveTypeModel->update();
                } elseif ($_POST['action'] === 'delete') {
                    $leaveTypeModel->id = $_POST['id'];
                    $leaveTypeModel->delete();
                }
            }
            $this->redirect('/payrollsystem/admin/leave_types');
        }

        $leaveTypes = $leaveTypeModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Leave Types',
            'content' => 'admin/leave_types',
            'leaveTypes' => $leaveTypes
        ]);
    }

    public function overtime() {
        $overtimeRequestModel = $this->model('OvertimeRequest');
        $departmentModel = $this->model('Department');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
            $overtimeRequestModel->handleRequest($_POST['id'], $_POST['action']);
            $this->redirect('/payrollsystem/admin/overtime');
        }

        $filters = [
            'department_id' => $_GET['department_id'] ?? '',
            'search' => $_GET['search'] ?? '',
            'date' => $_GET['date'] ?? ''
        ];
        
        $limit = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $total = $overtimeRequestModel->getTotalCount($filters);
        $overtimeRequests = $overtimeRequestModel->getFilteredRequests($filters, $limit, $offset);
        $departments = $departmentModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Overtime Management',
            'content' => 'admin/overtime',
            'overtimeRequests' => $overtimeRequests,
            'departments' => $departments,
            'filters' => $filters,
            'page' => $page,
            'total_pages' => ceil($total / $limit)
        ]);
    }

    public function overtime_assignments() {
        $db = new Database();
        $conn = $db->getConnection();
        
        $employeeModel = $this->model('Employee');
        $departmentModel = $this->model('Department');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if ($_POST['action'] === 'add') {
                $title = $_POST['title'];
                $date = $_POST['date'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];
                $reason = $_POST['reason'];
                $assign_type = $_POST['assign_type']; // 'department' or 'employee'
                $assigned_by = $_SESSION['user_id'];
                
                // Create Assignment Record
                $stmt = $conn->prepare("INSERT INTO overtime_assignments (title, date, start_time, end_time, reason, assigned_by) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $date, $start_time, $end_time, $reason, $assigned_by]);
                $assignment_id = $conn->lastInsertId();
                
                $emp_ids = [];
                if ($assign_type === 'department') {
                    $dept_id = $_POST['department_id'];
                    $empStmt = $conn->prepare("SELECT e.id, e.user_id FROM employees e JOIN users u ON e.user_id = u.id WHERE e.department_id = ? AND u.status = 'Active'");
                    $empStmt->execute([$dept_id]);
                    $emps = $empStmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach($emps as $emp) {
                        $emp_ids[] = $emp;
                    }
                } else {
                    $emp_id_list = $_POST['employee_ids'] ?? [];
                    foreach($emp_id_list as $e_id) {
                        $empStmt = $conn->prepare("SELECT id, user_id FROM employees WHERE id = ?");
                        $empStmt->execute([$e_id]);
                        $emp = $empStmt->fetch(PDO::FETCH_ASSOC);
                        if($emp) $emp_ids[] = $emp;
                    }
                }
                
                // Add employees and notify
                $notifModel = $this->model('Notification');
                foreach($emp_ids as $emp) {
                    $conn->prepare("INSERT INTO overtime_assignment_employees (assignment_id, employee_id) VALUES (?, ?)")
                         ->execute([$assignment_id, $emp['id']]);
                         
                    $notifModel->create(
                        $emp['user_id'],
                        "You have been assigned overtime: $title on $date",
                        'overtime',
                        '/employee/overtime',
                        'Overtime Assignment'
                    );
                }
                
                $this->redirect('/payrollsystem/admin/overtime_assignments');
            } elseif ($_POST['action'] === 'cancel') {
                $id = $_POST['id'];
                $conn->prepare("UPDATE overtime_assignments SET status = 'Cancelled' WHERE id = ?")->execute([$id]);
                $this->redirect('/payrollsystem/admin/overtime_assignments');
            }
        }

        // Fetch Assignments
        $stmt = $conn->query("SELECT oa.*, 
            (SELECT COUNT(*) FROM overtime_assignment_employees WHERE assignment_id = oa.id) as total_assigned 
            FROM overtime_assignments oa ORDER BY oa.date DESC");
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('layouts/main', [
            'title' => 'Overtime Assignments',
            'content' => 'admin/overtime_assignments',
            'assignments' => $assignments,
            'departments' => $departmentModel->getAll(),
            'employees' => $employeeModel->getAll()
        ]);
    }

    public function bonuses() {
        $db = new Database();
        $conn = $db->getConnection();
        $employeeModel = $this->model('Employee');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if ($_POST['action'] === 'add') {
                $employee_id = $_POST['employee_id'];
                $amount = $_POST['amount'];
                $type = $_POST['type'];
                $reason = $_POST['reason'];
                $date = $_POST['date'];
                $notes = $_POST['notes'] ?? '';

                $stmt = $conn->prepare("INSERT INTO bonuses (employee_id, amount, type, reason, date, notes) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$employee_id, $amount, $type, $reason, $date, $notes]);
                $this->redirect('/payrollsystem/admin/bonuses');
            } elseif ($_POST['action'] === 'delete') {
                $id = $_POST['id'];
                $conn->prepare("DELETE FROM bonuses WHERE id = ?")->execute([$id]);
                $this->redirect('/payrollsystem/admin/bonuses');
            }
        }

        $stmt = $conn->query("SELECT b.*, e.first_name, e.last_name, e.employee_code FROM bonuses b JOIN employees e ON b.employee_id = e.id ORDER BY b.date DESC");
        $bonuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('layouts/main', [
            'title' => 'Bonus Management',
            'content' => 'admin/bonuses',
            'bonuses' => $bonuses,
            'employees' => $employeeModel->getAll()
        ]);
    }

    public function deductions() {
        $db = new Database();
        $conn = $db->getConnection();
        $employeeModel = $this->model('Employee');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if ($_POST['action'] === 'add') {
                $employee_id = $_POST['employee_id'];
                $amount = $_POST['amount'];
                $type = $_POST['type'];
                $reason = $_POST['reason'];
                $date = $_POST['date'];
                $notes = $_POST['notes'] ?? '';

                $stmt = $conn->prepare("INSERT INTO deductions (employee_id, amount, type, reason, date, notes, created_by, status) VALUES (?, ?, ?, ?, ?, ?, 'Admin', 'Applied')");
                $stmt->execute([$employee_id, $amount, $type, $reason, $date, $notes]);
                $this->redirect('/payrollsystem/admin/deductions');
            } elseif ($_POST['action'] === 'delete') {
                $id = $_POST['id'];
                $conn->prepare("DELETE FROM deductions WHERE id = ?")->execute([$id]);
                $this->redirect('/payrollsystem/admin/deductions');
            }
        }

        $deductionModel = $this->model('Deduction');
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'type' => $_GET['type'] ?? '',
            'date_start' => $_GET['date_start'] ?? '',
            'date_end' => $_GET['date_end'] ?? '',
            'min_absent_days' => $_GET['min_absent_days'] ?? '',
            'max_absent_days' => $_GET['max_absent_days'] ?? ''
        ];
        
        $sort = $_GET['sort'] ?? 'date';
        $dir = $_GET['dir'] ?? 'DESC';
        $limit = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $total = $deductionModel->getTotalCount($filters);
        $deductions = $deductionModel->getFilteredDeductions($filters, $sort, $dir, $limit, $offset);

        $this->view('layouts/main', [
            'title' => 'Deduction Management',
            'content' => 'admin/deductions',
            'deductions' => $deductions,
            'employees' => $employeeModel->getAll(),
            'filters' => $filters,
            'sort' => $sort,
            'dir' => $dir,
            'page' => $page,
            'total_pages' => ceil($total / $limit)
        ]);
    }

    public function payroll() {
        $payrollModel = $this->model('Payroll');
        $db = new Database();
        $conn = $db->getConnection();

        $month = $_GET['month'] ?? date('n');
        $year = $_GET['year'] ?? date('Y');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if ($_POST['action'] === 'generate') {
                $genMonth = $_POST['month'];
                $genYear = $_POST['year'];
                $payrollModel->generatePayroll($genMonth, $genYear);
                $this->redirect("/payrollsystem/admin/payroll?month=$genMonth&year=$genYear");
            } elseif ($_POST['action'] === 'pay') {
                $payroll_id = $_POST['payroll_id'];
                $payment_method = $_POST['payment_method'];
                $payrollModel->markAsPaid($payroll_id, $payment_method);
                $this->redirect("/payrollsystem/admin/payroll?month=$month&year=$year");
            }
        }

        $payrolls = $payrollModel->getAll($month, $year);

        $this->view('layouts/main', [
            'title' => 'Monthly Payroll',
            'content' => 'admin/payroll',
            'payrolls' => $payrolls,
            'selectedMonth' => $month,
            'selectedYear' => $year
        ]);
    }

    public function payroll_slip($id = null) {
        if (!$id) $this->redirect('/payrollsystem/admin/payroll');
        
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("SELECT p.*, e.first_name, e.last_name, e.employee_code, e.join_date, e.phone, e.address, d.name as department_name, pos.name as position_name 
                                FROM payroll p 
                                JOIN employees e ON p.employee_id = e.id 
                                LEFT JOIN departments d ON e.department_id = d.id 
                                LEFT JOIN positions pos ON e.position_id = pos.id
                                WHERE p.id = ?");
        $stmt->execute([$id]);
        $payroll = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$payroll) $this->redirect('/payrollsystem/admin/payroll');

        // Note: Render without the main layout so it can be printed cleanly
        require_once __DIR__ . '/../views/admin/payroll_slip.php';
    }

    public function payroll_dashboard() {
        $db = new Database();
        $conn = $db->getConnection();
        
        $month = $_GET['month'] ?? date('n');
        $year = $_GET['year'] ?? date('Y');
        
        $stmt = $conn->prepare("SELECT COUNT(*) as total_employees, SUM(gross_salary) as total_payroll, SUM(ot_amount) as total_ot, SUM(bonus_amount) as total_bonus, SUM(deduction_amount) as total_deduction FROM payroll WHERE month = ? AND year = ?");
        $stmt->execute([$month, $year]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->view('layouts/main', [
            'title' => 'Payroll Dashboard',
            'content' => 'admin/payroll_dashboard',
            'stats' => $stats,
            'selectedMonth' => $month,
            'selectedYear' => $year
        ]);
    }

    public function payroll_reports() {
        $this->view('layouts/main', [
            'title' => 'Payroll Reports',
            'content' => 'admin/payroll_reports'
        ]);
    }

    public function holidays() {
        $holidayModel = $this->model('Holiday');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    $holidayModel->name = $_POST['name'];
                    $holidayModel->date = $_POST['date'];
                    $holidayModel->create();
                } elseif ($_POST['action'] === 'edit') {
                    $holidayModel->id = $_POST['id'];
                    $holidayModel->name = $_POST['name'];
                    $holidayModel->date = $_POST['date'];
                    $holidayModel->update();
                } elseif ($_POST['action'] === 'delete') {
                    $holidayModel->id = $_POST['id'];
                    $holidayModel->delete();
                }
            }
            $this->redirect('/payrollsystem/admin/holidays');
        }

        $holidays = $holidayModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Holiday Management',
            'content' => 'admin/holidays',
            'holidays' => $holidays
        ]);
    }

    public function settings() {
        $settingModel = $this->model('Setting');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $settingModel->company_name = $_POST['company_name'];
            $settingModel->office_start_time = $_POST['office_start_time'];
            $settingModel->office_end_time = $_POST['office_end_time'];
            $settingModel->auto_checkout_time = $_POST['auto_checkout_time'];
            $settingModel->late_time = $_POST['late_time'];
            $settingModel->working_hours = $_POST['working_hours'];
            $settingModel->weekday_ot_rate = $_POST['weekday_ot_rate'];
            $settingModel->weekend_ot_rate = $_POST['weekend_ot_rate'];
            $settingModel->holiday_ot_rate = $_POST['holiday_ot_rate'];
            $settingModel->max_ot_hours = $_POST['max_ot_hours'];
            $settingModel->unpaid_leave_rules = $_POST['unpaid_leave_rules'] ?? '';
            $settingModel->half_day_leave_rules = $_POST['half_day_leave_rules'] ?? '';
            $settingModel->absent_deduction_rate = $_POST['absent_deduction_rate'] ?? 0;
            $settingModel->half_day_deduction_rate = $_POST['half_day_deduction_rate'] ?? 0;
            $settingModel->unpaid_leave_deduction_rate = $_POST['unpaid_leave_deduction_rate'] ?? 0;
            $settingModel->auto_deduction_enabled = isset($_POST['auto_deduction_enabled']) ? 1 : 0;
            $settingModel->deduction_calculation_method = $_POST['deduction_calculation_method'] ?? 'Salary-Based';
            $settingModel->late_deduction_rules = $_POST['late_deduction_rules'] ?? '';
            $settingModel->excess_paid_leave_deduction_rules = $_POST['excess_paid_leave_deduction_rules'] ?? '';
            $settingModel->custom_deduction_rules = $_POST['custom_deduction_rules'] ?? '';

            $settingModel->update();
            $this->redirect('/payrollsystem/admin/settings');
        }

        $settings = $settingModel->getSettings();

        $this->view('layouts/main', [
            'title' => 'System Settings',
            'content' => 'admin/settings',
            'settings' => $settings
        ]);
    }

    public function reports() {
        $this->view('layouts/main', [
            'title' => 'Reports',
            'content' => 'admin/reports'
        ]);
    }

    public function notifications() {
        $this->view('layouts/main', [
            'title' => 'Notifications',
            'content' => 'admin/notifications'
        ]);
    }
}
