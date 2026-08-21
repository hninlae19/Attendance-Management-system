<?php
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../core/HolidayHelper.php';

class AdminController extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
            $this->redirect('/payrollsystem/auth/login');
        }
    }

    public function dashboardApi() {
        // Same data as index but output as JSON for AJAX
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        $employees = $employeeModel->getAll();
        $totalEmployees = count($employees);
        $activeEmployees = count(array_filter($employees, fn($e) => $e['Status'] === 'Active'));
        $attendance = $attendanceModel->getAllRecords();
        $today = date('Y-m-d');
        $presentToday = count(array_filter($attendance, fn($a) => $a['AttendanceDate'] === $today && $a['Status'] === 'Present'));
        $lateToday = count(array_filter($attendance, fn($a) => $a['AttendanceDate'] === $today && $a['Status'] === 'Late'));
        $absentToday = count(array_filter($attendance, fn($a) => $a['AttendanceDate'] === $today && ($a['Status'] === 'Absent' || $a['Status'] === 'Half Day')));
        // Employees on approved leave today
        $db = new Database();
        $conn = $db->getConnection();
        $onLeaveStmt = $conn->prepare("SELECT COUNT(DISTINCT EmpID) FROM LeaveRequest WHERE ? BETWEEN StartDate AND EndDate AND Status = 'Approved'");
        $onLeaveStmt->execute([$today]);
        $employeesOnLeave = (int)($onLeaveStmt->fetchColumn() ?: 0);
        // Pending leaves
        $pendingLeaveStmt = $conn->prepare("SELECT COUNT(*) FROM LeaveRequest WHERE Status = 'Pending'");
        $pendingLeaveStmt->execute();
        $pendingLeaves = (int)($pendingLeaveStmt->fetchColumn() ?: 0);
        // Pending overtime assignments
        $pendingOvertime = $this->model('OvertimeAssign')->getPendingCount();
        // Password reset requests
        $pendingResetsCount = (int)($conn->query("SELECT COUNT(*) FROM employee WHERE PasswordResetRequest = 1")->fetchColumn() ?: 0);
        // Monthly payroll
        $payrollMonthStr = date('F Y');
        $payrollStmt = $conn->prepare("SELECT SUM(NetSalary) FROM Payroll WHERE PayrollMonth = ?");
        $payrollStmt->execute([$payrollMonthStr]);
        $monthlyPayroll = (float)($payrollStmt->fetchColumn() ?: 0);
        // Monthly bonuses
        $bonusStmt = $conn->prepare("SELECT SUM(Amount) FROM EmpBonous WHERE MONTH(BonusDate) = ? AND YEAR(BonusDate) = ?");
        $bonusStmt->execute([date('n'), date('Y')]);
        $monthlyBonus = (float)($bonusStmt->fetchColumn() ?: 0);
        $recentAttendance = array_slice($attendance, 0, 5);
        header('Content-Type: application/json');
        echo json_encode([
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'presentToday' => $presentToday,
            'lateToday' => $lateToday,
            'absentToday' => $absentToday,
            'employeesOnLeave' => $employeesOnLeave,
            'monthlyPayroll' => $monthlyPayroll,
            'monthlyBonus' => $monthlyBonus,
            'pendingLeaves' => $pendingLeaves,
            'pendingOvertime' => $pendingOvertime,
            'pendingResets' => $pendingResetsCount,
            'recentAttendance' => $recentAttendance
        ]);
    }

    public function index() {
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');

        $employees = $employeeModel->getAll();
        $totalEmployees = count($employees);
        $activeEmployees = count(array_filter($employees, function($e) { return $e['Status'] == 'Active'; }));

        $attendance = $attendanceModel->getAllRecords();
        $today = date('Y-m-d');
        $presentToday = count(array_filter($attendance, function($a) use ($today) { return $a['AttendanceDate'] == $today && $a['Status'] == 'Present'; }));
        $lateToday = count(array_filter($attendance, function($a) use ($today) { return $a['AttendanceDate'] == $today && $a['Status'] == 'Late'; }));
        $absentToday = count(array_filter($attendance, function($a) use ($today) { return $a['AttendanceDate'] == $today && ($a['Status'] == 'Absent' || $a['Status'] == 'Half Day'); }));
        
        $recentAttendance = array_slice($attendance, 0, 5);

        // Fetch additional metrics
        $db = new Database();
        $conn = $db->getConnection();
        
        // Monthly Payroll (Sum of NetSalary for current month)
        $payrollMonthStr = date('F Y');
        $payrollQuery = $conn->prepare("SELECT SUM(NetSalary) FROM Payroll WHERE PayrollMonth = ?");
        $payrollQuery->execute([$payrollMonthStr]);
        $monthlyPayroll = (float)($payrollQuery->fetchColumn() ?: 0);

        // Monthly Bonuses (Sum of Amount for current month)
        $bonusQuery = $conn->prepare("SELECT SUM(Amount) FROM EmpBonous WHERE MONTH(BonusDate) = ? AND YEAR(BonusDate) = ?");
        $bonusQuery->execute([date('n'), date('Y')]);
        $monthlyBonus = (float)($bonusQuery->fetchColumn() ?: 0);

        // Pending Leaves
        $leaveQuery = $conn->prepare("SELECT COUNT(*) FROM LeaveRequest WHERE Status = 'Pending'");
        $leaveQuery->execute();
        $pendingLeaves = (int)($leaveQuery->fetchColumn() ?: 0);

        // Employees currently on leave
        $onLeaveQuery = $conn->prepare("SELECT COUNT(DISTINCT EmpID) FROM LeaveRequest WHERE ? BETWEEN StartDate AND EndDate AND Status = 'Approved'");
        $onLeaveQuery->execute([$today]);
        $employeesOnLeave = (int)($onLeaveQuery->fetchColumn() ?: 0);
        $overtimeModel = $this->model('OvertimeAssign');
        $pendingOvertime = $overtimeModel->getPendingCount();

        $pendingResetsCount = $conn->query("SELECT COUNT(*) FROM employee WHERE PasswordResetRequest = 1")->fetchColumn();

        $this->view('layouts/main', [
            'title' => 'Dashboard',
            'content' => 'admin/dashboard',
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'presentToday' => $presentToday,
            'lateToday' => $lateToday,
            'absentToday' => $absentToday,
            'employeesOnLeave' => $employeesOnLeave,
            'monthlyPayroll' => $monthlyPayroll,
            'monthlyBonus' => $monthlyBonus,
            'recentAttendance' => $recentAttendance,
            'pendingLeaves' => $pendingLeaves,
            'pendingOvertime' => $pendingOvertime,
            'pendingResets' => $pendingResetsCount
        ]);
    }

    public function departments() {
        $departmentModel = $this->model('Department');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    if ($departmentModel->nameExists($_POST['name'])) {
                        $this->redirect('/payrollsystem/admin/departments?error=duplicate');
                        return;
                    }
                    $departmentModel->DeptName = $_POST['name'];
                    $departmentModel->create();
                } elseif ($_POST['action'] === 'edit') {
                    if ($departmentModel->nameExists($_POST['name'], $_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/departments?error=duplicate');
                        return;
                    }
                    $departmentModel->DeptID = $_POST['id'];
                    $departmentModel->DeptName = $_POST['name'];
                    $departmentModel->update();
                } elseif ($_POST['action'] === 'delete') {
                    if ($departmentModel->hasPositions($_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/departments?error=in_use');
                        return;
                    }
                    if (!$departmentModel->delete($_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/departments?error=in_use');
                        return;
                    }
                }
            }
            $this->redirect('/payrollsystem/admin/departments');
        }

        $departments = $departmentModel->getAll();
        
        // Server-side sorting support
        $sort = $_GET['sort'] ?? 'id';
        $order = strtolower($_GET['order'] ?? 'desc'); // Default to ID descending
        
        usort($departments, function($a, $b) use ($sort, $order) {
            if ($sort === 'name') {
                $cmp = strcasecmp($a['DeptName'], $b['DeptName']);
            } else {
                $cmp = (int)$a['DeptID'] <=> (int)$b['DeptID'];
            }
            return $order === 'desc' ? -$cmp : $cmp;
        });

        $this->view('layouts/main', [
            'title' => 'Departments',
            'content' => 'admin/departments',
            'departments' => $departments,
            'currentSort' => $sort,
            'currentOrder' => $order
        ]);
    }
    
    public function positions() {
        $positionModel = $this->model('Position');
        $departmentModel = $this->model('Department');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    if ($positionModel->nameExists($_POST['name'])) {
                        $this->redirect('/payrollsystem/admin/positions?error=duplicate');
                        return;
                    }
                    $positionModel->PositionName = $_POST['name'];
                    $positionModel->DeptID = $_POST['department_id'];
                    $positionModel->BasicSalary = $_POST['basic_salary'] ?? 0;
                    $positionModel->create();
                } elseif ($_POST['action'] === 'edit') {
                    if ($positionModel->nameExists($_POST['name'], $_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/positions?error=duplicate');
                        return;
                    }
                    $positionModel->PositionID = $_POST['id'];
                    $positionModel->PositionName = $_POST['name'];
                    $positionModel->DeptID = $_POST['department_id'];
                    $positionModel->BasicSalary = $_POST['basic_salary'] ?? 0;
                    $positionModel->update();
                } elseif ($_POST['action'] === 'delete') {
                    if ($positionModel->hasEmployees($_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/positions?error=in_use');
                        return;
                    }
                    if (!$positionModel->delete($_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/positions?error=in_use');
                        return;
                    }
                }
            }
            $this->redirect('/payrollsystem/admin/positions');
        }

        $positions = $positionModel->getAll();
        $departments = $departmentModel->getAll();
        
        // Server-side sorting support
        $sort = $_GET['sort'] ?? 'id';
        $order = strtolower($_GET['order'] ?? 'desc'); // Default to ID descending
        
        usort($positions, function($a, $b) use ($sort, $order) {
            if ($sort === 'name' || $sort === 'position_name') {
                $cmp = strcasecmp($a['PositionName'], $b['PositionName']);
            } elseif ($sort === 'dept' || $sort === 'dept_name') {
                $cmp = strcasecmp($a['DeptName'] ?? '', $b['DeptName'] ?? '');
            } elseif ($sort === 'salary') {
                $cmp = (float)$a['BasicSalary'] <=> (float)$b['BasicSalary'];
            } else {
                $cmp = (int)$a['PositionID'] <=> (int)$b['PositionID'];
            }
            return $order === 'desc' ? -$cmp : $cmp;
        });

        $this->view('layouts/main', [
            'title' => 'Positions',
            'content' => 'admin/positions',
            'positions' => $positions,
            'departments' => $departments,
            'currentSort' => $sort,
            'currentOrder' => $order
        ]);
    }
    
    public function employee_salary_history($id = null) {
        if (!$id) {
            $this->redirect('/payrollsystem/admin/employees');
            return;
        }

        $employeeModel = $this->model('Employee');
        $employee = $employeeModel->getEmployeeById($id);
        if (!$employee) {
            $this->redirect('/payrollsystem/admin/employees');
            return;
        }

        $payrollModel = $this->model('Payroll');
        $payrolls = $payrollModel->getByEmployee($id);

        $this->view('layouts/main', [
            'title' => 'Employee Salary History',
            'content' => 'admin/employee_salary_history',
            'employee' => $employee,
            'payrolls' => $payrolls
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
                    $phone = $_POST['phone'];
                    if (!preg_match('/^[0-9\-\+\s\(\)]{7,20}$/', $phone)) {
                        $_SESSION['error'] = 'Invalid phone number format.';
                        $this->redirect('/payrollsystem/admin/employees');
                        return;
                    }
                    if (strlen($_POST['password']) < 6) {
                        $_SESSION['error'] = 'Password must be at least 6 characters.';
                        $this->redirect('/payrollsystem/admin/employees');
                        return;
                    }
                    if ($employeeModel->emailExists($_POST['email'])) {
                        $_SESSION['error'] = 'Email address already exists.';
                        $this->redirect('/payrollsystem/admin/employees');
                        return;
                    }

                    $employeeModel->FirstName = $_POST['first_name'];
                    $employeeModel->LastName = $_POST['last_name'];
                    $employeeModel->Gender = $_POST['gender'] ?? 'Other';
                    $employeeModel->Email = $_POST['email'];
                    $employeeModel->Password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $employeeModel->PhoneNumber = $phone;
                    $employeeModel->Address = $_POST['address'];
                    $employeeModel->PositionID = $_POST['position_id'];
                    $employeeModel->JoinDate = $_POST['join_date'];
                    $employeeModel->Status = 'Active';
                    
                    $employeeModel->create();
                    $_SESSION['success'] = 'Employee added successfully.';
                } elseif ($_POST['action'] === 'delete') {
                    $employeeModel->updateStatus($_POST['id'], 'Inactive');
                    $_SESSION['success'] = 'Employee deactivated successfully.';
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

    public function employee($id = null) {
        if (!$id) {
            $this->redirect('/payrollsystem/admin/employees');
        }

        $employeeModel = $this->model('Employee');
        $departmentModel = $this->model('Department');
        $positionModel = $this->model('Position');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action']) && $_POST['action'] === 'edit') {
                $existingEmployee = $employeeModel->getEmployeeById($id);
                
                $phone = $_POST['phone'];
                if (!preg_match('/^[0-9\-\+\s\(\)]{7,20}$/', $phone)) {
                    $_SESSION['error'] = 'Invalid phone number format.';
                    $this->redirect('/payrollsystem/admin/employee/' . $id);
                    return;
                }

                $employeeModel->EmpID = $id;
                $employeeModel->FirstName = $_POST['first_name'];
                $employeeModel->LastName = $_POST['last_name'];
                $employeeModel->Gender = $_POST['gender'];
                // Email is immutable on update
                $employeeModel->Email = $existingEmployee['Email'];
                $employeeModel->JoinDate = $_POST['join_date'];
                $employeeModel->PhoneNumber = $phone;
                $employeeModel->Address = $_POST['address'];
                $employeeModel->PositionID = $_POST['position_id'];
                $employeeModel->Status = $_POST['status'];
                
                if (!empty($_POST['password'])) {
                    if (strlen($_POST['password']) < 6) {
                        $_SESSION['error'] = 'Password must be at least 6 characters.';
                        $this->redirect('/payrollsystem/admin/employee/' . $id);
                        return;
                    }
                    $employeeModel->Password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $employeeModel->update();
                $_SESSION['success'] = 'Employee updated successfully.';
                $this->redirect('/payrollsystem/admin/employee/' . $id);
            }
        }

        $employee = $employeeModel->getEmployeeById($id);
        
        if (!$employee) {
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
        $attendanceModel = $this->model('Attendance');
        $departmentModel = $this->model('Department');
        $employeeModel = $this->model('Employee');
        
        $records = $attendanceModel->getAllRecords();
        $departments = $departmentModel->getAll();
        $employees = $employeeModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Attendance Management',
            'content' => 'admin/attendance',
            'records' => $records,
            'departments' => $departments,
            'employees' => $employees,
            'corrections' => []
        ]);
    }

    public function attendanceApi() {
        header('Content-Type: application/json');
        
        $attendanceModel = $this->model('Attendance');
        $employeeModel = $this->model('Employee');
        $departmentModel = $this->model('Department');
        $overtimeModel = $this->model('OvertimeAssign');
        
        $allRecords = $attendanceModel->getAllRecords();
        $employees = $employeeModel->getAll();
        $departments = $departmentModel->getAll();
        $overtimes = $overtimeModel->getAll();
        
        $otMap = [];
        foreach($overtimes as $ot) {
            $otMap[$ot['EmpID'] . '_' . $ot['OvertimeDate']] = $ot['TotalHours'];
        }
        
        $empMap = [];
        foreach($employees as $emp) {
            $empMap[$emp['EmpID']] = $emp;
        }
        $deptMap = [];
        foreach($departments as $dept) {
            $deptMap[$dept['DeptID']] = $dept;
        }
        
        $data = [];
        foreach($allRecords as $record) {
            $emp = $empMap[$record['EmpID']] ?? null;
            $deptId = $emp['DeptID'] ?? null;
            $dept = $deptMap[$deptId] ?? null;
            
            $working_hours = 0;
            $calculated_status = $record['Status'];
            
            if ($record['CheckInTime'] && $record['CheckOutTime']) {
                $in = strtotime($record['CheckInTime']);
                $out = strtotime($record['CheckOutTime']);
                $working_hours = round(abs($out - $in) / 3600, 2);
                
                $calculated_status = $attendanceModel->calculateStatus($record['CheckInTime'], $record['CheckOutTime']);
            } elseif ($record['CheckInTime'] && empty($record['CheckOutTime'])) {
                $in = strtotime($record['CheckInTime']);
                $now = strtotime(date('H:i:s'));
                $working_hours = round(abs($now - $in) / 3600, 2);
                $calculated_status = 'Present';
            } elseif (empty($record['CheckInTime']) && empty($record['CheckOutTime'])) {
                $calculated_status = 'Absent';
            } else if ($record['Status'] === 'Absent' || $record['Status'] === 'Full-Day Absence' || $record['Status'] === 'Full-day absent') {
                $calculated_status = 'Absent';
            } else if ($record['Status'] === 'Half Day' || $record['Status'] === 'Half-Day Absence' || $record['Status'] === 'Half-day absent') {
                $calculated_status = 'Half Day';
            }
            
            $ot_hours = $otMap[$record['EmpID'] . '_' . $record['AttendanceDate']] ?? 0;
            
            $data[] = [
                'id' => $record['AttendanceID'],
                'employee_id' => $record['EmpID'],
                'first_name' => $record['FirstName'],
                'last_name' => $record['LastName'],
                'employee_code' => str_pad($record['EmpID'], 4, '0', STR_PAD_LEFT),
                'department_id' => $deptId,
                'department_name' => $dept['DeptName'] ?? 'N/A',
                'PositionName' => 'Staff', 
                'date' => $record['AttendanceDate'],
                'check_in' => $record['CheckInTime'],
                'check_out' => $record['CheckOutTime'],
                'is_auto_checkout' => $record['is_auto_checkout'] ?? 0, 
                'working_hours' => $working_hours,
                'ot_hours' => $ot_hours, 
                'status' => $calculated_status,
                'late_minutes' => Attendance::calculateLateMinutes($record['CheckInTime'])
            ];
        }
        
        $filtered = array_filter($data, function($item) {
            $match = true;
            if (!empty($_GET['date_start']) && $item['date'] < $_GET['date_start']) $match = false;
            if (!empty($_GET['date_end']) && $item['date'] > $_GET['date_end']) $match = false;
            if (!empty($_GET['department_id']) && $item['department_id'] != $_GET['department_id']) $match = false;
            if (!empty($_GET['employee_id']) && $item['employee_id'] != $_GET['employee_id']) $match = false;
            if (!empty($_GET['status'])) {
                $f = strtolower(trim($_GET['status']));
                $s = strtolower(trim($item['status'] ?? ''));
                if ($f !== $s) $match = false;
            }
            if (!empty($_GET['search'])) {
                $search = strtolower($_GET['search']);
                $name = strtolower($item['first_name'] . ' ' . $item['last_name']);
                if (strpos($name, $search) === false && strpos(strtolower($item['employee_code']), $search) === false && strpos(strtolower($item['department_name']), $search) === false) {
                    $match = false;
                }
            }
            return $match;
        });
        
        $total = count($filtered);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $total_pages = ceil($total / $limit);
        
        $offset = ($page - 1) * $limit;
        $paginated = array_slice($filtered, $offset, $limit);
        
        echo json_encode([
            'data' => array_values($paginated),
            'total' => $total,
            'total_pages' => max(1, $total_pages)
        ]);
        exit;
    }

    public function leaves() {
        $leaveRequestModel = $this->model('LeaveRequest');
        $departmentModel = $this->model('Department');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
            $status = $_POST['action'] === 'approve' ? 'Approved' : ($_POST['action'] === 'reject' ? 'Rejected' : 'Pending');
            $leaveRequestModel->updateStatus($_POST['id'], $status);

            // Fetch the leave request to get EmpID
            $leave = $leaveRequestModel->getById($_POST['id']);
            if ($leave) {
                $notifModel = $this->model('Notification');
                $adminName = $_SESSION['email'] ?? 'Admin'; // Admin does not have first_name/last_name in session currently
                $msg = "Your leave request for {$leave['LeaveType']} from {$leave['StartDate']} to {$leave['EndDate']} has been {$status}.";
                $type = $status === 'Approved' ? 'success' : 'error';
                $notifModel->create($leave['EmpID'], $msg, $type, '/employee/leaves', "Leave Request {$status}", $_SESSION['user_id']);
            }

            $this->redirect('/payrollsystem/admin/leaves');
        }

        $filters = [
            'search' => $_GET['search'] ?? '',
            'DeptID' => $_GET['department_id'] ?? '',
            'status' => $_GET['status'] ?? '',
            'leave_type' => $_GET['leave_type'] ?? '',
            'date' => $_GET['date'] ?? ''
        ];

        // Filter the fetched records
        $leaveRequests = $leaveRequestModel->getAll();
        if (!empty($filters['search']) || !empty($filters['DeptID']) || !empty($filters['date']) || !empty($filters['status']) || !empty($filters['leave_type'])) {
            $leaveRequests = array_filter($leaveRequests, function($lr) use ($filters) {
                $match = true;
                if (!empty($filters['search'])) {
                    $search = strtolower($filters['search']);
                    $name = strtolower($lr['FirstName'] . ' ' . $lr['LastName']);
                    $empCode = strtolower('EMP-' . str_pad($lr['EmpID'], 4, '0', STR_PAD_LEFT));
                    if (strpos($name, $search) === false && strpos($empCode, $search) === false) {
                        $match = false;
                    }
                }
                
                if ($match && !empty($filters['DeptID'])) {
                    if ($lr['DeptID'] != $filters['DeptID']) {
                        $match = false;
                    }
                }

                if ($match && !empty($filters['status'])) {
                    if ($lr['Status'] != $filters['status']) {
                        $match = false;
                    }
                }

                if ($match && !empty($filters['leave_type'])) {
                    if ($lr['LeaveTypeID'] != $filters['leave_type']) {
                        $match = false;
                    }
                }
                
                if ($match && !empty($filters['date'])) {
                    $filterDate = $filters['date'];
                    if ($filterDate < $lr['StartDate'] || $filterDate > $lr['EndDate']) {
                        $match = false;
                    }
                }
                
                return $match;
            });
            $leaveRequests = array_values($leaveRequests);
        }

        $departments = $departmentModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Leave Management',
            'content' => 'admin/leaves',
            'leaveRequests' => $leaveRequests,
            'departments' => $departments,
            'filters' => $filters,
            'page' => 1,
            'total_pages' => 1
        ]);
    }

    public function leave_types() {
        $leaveTypeModel = $this->model('LeaveType');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    if ($leaveTypeModel->nameExists($_POST['name'])) {
                        $this->redirect('/payrollsystem/admin/leave_types?error=duplicate');
                        return;
                    }
                    $leaveTypeModel->LeaveType = $_POST['name'];
                    $leaveTypeModel->DaysAllowed = (int)$_POST['days'];
                    $leaveTypeModel->IsPaid = isset($_POST['is_paid']) ? 1 : 0;
                    $leaveTypeModel->DeductionRate = (float)($_POST['deduction_rate'] ?? 0);
                    $leaveTypeModel->DurationMonths = (int)($_POST['duration_months'] ?? 0);
                    $leaveTypeModel->create();
                } elseif ($_POST['action'] === 'edit') {
                    if ($leaveTypeModel->nameExists($_POST['name'], $_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/leave_types?error=duplicate');
                        return;
                    }
                    $leaveTypeModel->LeaveTypeID = $_POST['id'];
                    $leaveTypeModel->LeaveType = $_POST['name'];
                    $leaveTypeModel->DaysAllowed = (int)$_POST['days'];
                    $leaveTypeModel->IsPaid = isset($_POST['is_paid']) ? 1 : 0;
                    $leaveTypeModel->DeductionRate = (float)($_POST['deduction_rate'] ?? 0);
                    $leaveTypeModel->DurationMonths = (int)($_POST['duration_months'] ?? 0);
                    $leaveTypeModel->update();
                } elseif ($_POST['action'] === 'delete') {
                    if ($leaveTypeModel->hasUsage($_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/leave_types?error=in_use');
                        return;
                    }
                    if (!$leaveTypeModel->delete($_POST['id'])) {
                        $this->redirect('/payrollsystem/admin/leave_types?error=in_use');
                        return;
                    }
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

    public function overtime_assignments() {
        $overtimeModel = $this->model('OvertimeAssign');
        $employeeModel = $this->model('Employee');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
                    $assignType = $_POST['assign_type'] ?? 'individual';
                    $empIdInput = $_POST['emp_id'] ?? null;
                    $deptIdInput = $_POST['assign_dept_id'] ?? null;
                    
                    $otDate = $_POST['overtime_date'];
                    $startTime = $_POST['start_time'];
                    $endTime = $_POST['end_time'];
                    
                    if (HolidayHelper::isPublicHoliday($otDate)) {
                        $rateMultiplier = 3.0;
                    } elseif (HolidayHelper::isWeekend($otDate)) {
                        $rateMultiplier = 2.0;
                    } else {
                        $rateMultiplier = 1.5;
                    }
                    
                    if ($_POST['action'] === 'add') {
                        $today = date('Y-m-d');
                        if ($otDate < $today) {
                            $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('New overtime assignments must be for the current date or a future date.'));
                            return;
                        }
                    }
                    
                    $db = new Database();
                    $conn = $db->getConnection();
                    
                    $employeesToProcess = [];
                    $employees = $employeeModel->getAll();
                    if ($assignType === 'department') {
                        foreach ($employees as $e) {
                            if ($e['DeptID'] == $deptIdInput && $e['Status'] === 'Active') {
                                $employeesToProcess[] = $e['EmpID'];
                            }
                        }
                        if (empty($employeesToProcess)) {
                            $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('No active employees found in selected department.'));
                            return;
                        }
                    } else {
                        $isActive = false;
                        foreach ($employees as $e) {
                            if ($e['EmpID'] == $empIdInput && $e['Status'] === 'Active') {
                                $isActive = true; break;
                            }
                        }
                        if (!$isActive) {
                            $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Cannot assign overtime to inactive employee.'));
                            return;
                        }
                        $employeesToProcess[] = $empIdInput;
                    }
                    
                    $isHoliday = HolidayHelper::isPublicHoliday($otDate);
                    $dayOfWeek = date('N', strtotime($otDate));
                    $isWeekend = HolidayHelper::isWeekend($otDate);
                    $isWorkingDay = HolidayHelper::isWorkingDay($otDate);
                    
                    // Time rules
                    $startUnix = strtotime("1970-01-01 $startTime");
                    $endUnix = strtotime("1970-01-01 $endTime");
                    if ($endUnix < $startUnix) {
                        $endUnix += 86400; // overnight
                    }
                    $hours = round(($endUnix - $startUnix) / 3600, 2);
                    
                    if ($isWorkingDay) {
                        $minStart = strtotime("1970-01-01 17:00:00");
                        $maxEnd = strtotime("1970-01-01 21:00:00");
                        if ($startUnix < $minStart || $endUnix > $maxEnd) {
                            $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Overtime is only allowed between 5:00 PM and 9:00 PM on working days.'));
                            return;
                        }
                    } else {
                        $minStart = strtotime("1970-01-01 09:00:00");
                        $maxEnd = strtotime("1970-01-01 17:00:00");
                        if ($startUnix < $minStart || $endUnix > $maxEnd) {
                            $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Overtime is only allowed between 9:00 AM and 5:00 PM on holidays/weekends.'));
                            return;
                        }
                    }
                    
                    if ($hours > 4) {
                        $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Daily overtime limit of 4 hours exceeded.'));
                        return;
                    }
                    
                    $leaveModel = $this->model('LeaveRequest');
                    
                    // Validate each employee
                    foreach ($employeesToProcess as $empId) {
                        // Attendance Check (Working Days)
                        if ($isWorkingDay) {
                            $stmt = $conn->prepare("SELECT * FROM attendance WHERE EmpID = :emp AND AttendanceDate = :date AND CheckInTime IS NOT NULL");
                            $stmt->execute([':emp' => $empId, ':date' => $otDate]);
                            if ($stmt->rowCount() == 0) {
                                $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Employee has not checked in today.'));
                                return;
                            }
                        }
                        
                        // Leave Check
                        $leaves = $leaveModel->getByEmployee($empId);
                        foreach ($leaves as $leave) {
                            if ($leave['Status'] === 'Approved' && $otDate >= $leave['StartDate'] && $otDate <= $leave['EndDate']) {
                                $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Employee is on approved leave.'));
                                return;
                            }
                        }
                        
                        // Overlap Check
                        $excludeId = ($_POST['action'] === 'edit') ? $_POST['id'] : null;
                        $existing = $overtimeModel->getAssignmentsByDate($empId, $otDate, $excludeId);
                        foreach ($existing as $ex) {
                            if (!$ex['StartTime'] || !$ex['EndTime']) continue; // skip old malformed data
                            $exStartTimeOnly = date('H:i:s', strtotime($ex['StartTime']));
                            $exEndTimeOnly = date('H:i:s', strtotime($ex['EndTime']));
                            $exStart = strtotime("1970-01-01 $exStartTimeOnly");
                            $exEnd = strtotime("1970-01-01 $exEndTimeOnly");
                            if ($exEnd < $exStart) $exEnd += 86400;
                            
                            if ($startUnix < $exEnd && $endUnix > $exStart) {
                                $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Overtime time range overlaps with an existing assignment.'));
                                return;
                            }
                        }
                        
                        // Monthly Limit
                        $otYear = date('Y', strtotime($otDate));
                        $otMonth = date('m', strtotime($otDate));
                        $currentMonthlyHours = $overtimeModel->getMonthlyHours($empId, $otYear, $otMonth, $excludeId);
                        if (($currentMonthlyHours + $hours) > 60) {
                            $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Monthly overtime limit of 60 hours exceeded.'));
                            return;
                        }
                    }
                    
                    // Create/Update records
                    foreach ($employeesToProcess as $empId) {
                        // Fetch basic salary to calculate hourly rate
                        $empStmt = $conn->prepare("SELECT p.BasicSalary FROM employee e JOIN position p ON e.PositionID = p.PositionID WHERE e.EmpID = :emp");
                        $empStmt->execute([':emp' => $empId]);
                        $basicSalary = $empStmt->fetchColumn() ?: 0;
                        
                        $dailyRate = $basicSalary / 30;
                        $hourlyRate = $dailyRate / 8;
                        $otAmount = $hours * $hourlyRate * $rateMultiplier;

                        $overtimeModel->EmpID = $empId;
                        $overtimeModel->OvertimeDate = $otDate;
                        $overtimeModel->StartTime = date('Y-m-d H:i:s', strtotime("$otDate $startTime:00"));
                        $overtimeModel->EndTime = date('Y-m-d H:i:s', strtotime("$otDate $endTime:00" . (strtotime("1970-01-01 $endTime") < strtotime("1970-01-01 $startTime") ? " +1 day" : "")));
                        $overtimeModel->TotalHours = $hours;
                        $overtimeModel->RateMultiplier = $rateMultiplier;
                        $overtimeModel->OTAmount = $otAmount;
                        
                        if ($_POST['action'] === 'add') {
                            $overtimeModel->create();
                            $notifModel = $this->model('Notification');
                            $msg = "You have been assigned overtime on {$otDate} from {$startTime} to {$endTime}.";
                            $notifModel->create($empId, $msg, 'info', '/employee/overtime', 'New Overtime Assignment', $_SESSION['user_id']);
                        } else {
                            $overtimeModel->OvertimeID = $_POST['id'];
                            $overtimeModel->update();
                        }
                    }
                } elseif ($_POST['action'] === 'approve' || $_POST['action'] === 'cancel' || $_POST['action'] === 'no_show') {
                    $status = 'Completed';
                    if ($_POST['action'] === 'approve') $status = 'Completed';
                    elseif ($_POST['action'] === 'cancel') $status = 'Cancelled';
                    elseif ($_POST['action'] === 'no_show') $status = 'NoOT';

                    $appBy = $_POST['action'] === 'approve' ? $_SESSION['user_id'] : null;
                    $overtimeModel->updateStatus($_POST['id'], $status, $appBy);
                } elseif ($_POST['action'] === 'delete') {
                    $overtimeModel->delete($_POST['id']);
                }
            }
            $this->redirect('/payrollsystem/admin/overtime_assignments');
        }

        $assignments = $overtimeModel->getAll();
        $employees = $employeeModel->getAll();
        $departmentModel = $this->model('Department');
        $departments = $departmentModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Overtime Assignments',
            'content' => 'admin/overtime_assignments',
            'assignments' => $assignments,
            'employees' => $employees,
            'departments' => $departments,
            'error' => $_GET['error'] ?? null
        ]);
    }

    public function bonuses() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            $empBonousModel = $this->model('EmpBonous');
            
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'add') {
                    $empId = $_POST['employee_id'];
                    $amount = $_POST['amount'];
                    $date = $_POST['date'];
                    $type = $_POST['type'];
                    if ($type === 'Other' && !empty($_POST['custom_type'])) {
                        $type = trim($_POST['custom_type']);
                    }
                    
                    // Find or create Bonus type in Bonous table
                    $db = new Database();
                    $conn = $db->getConnection();
                    
                    $stmt = $conn->prepare("SELECT BonousID FROM bonous WHERE BonusType = :type LIMIT 1");
                    $stmt->execute([':type' => $type]);
                    $bonusId = $stmt->fetchColumn();
                    
                    if (!$bonusId) {
                        $stmt = $conn->prepare("INSERT INTO bonous (BonusType) VALUES (:type)");
                        $stmt->execute([':type' => $type]);
                        $bonusId = $conn->lastInsertId();
                    }
                    
                    $empBonousModel->EmpID = $empId;
                    $empBonousModel->Amount = $amount;
                    $empBonousModel->BonusDate = $date;
                    $empBonousModel->BonousID = $bonusId;
                    
                    $empBonousModel->create();
                    
                } elseif ($_POST['action'] === 'delete') {
                    $empBonousModel->delete($_POST['id']);
                }
            }
            $this->redirect('/payrollsystem/admin/bonuses');
            return;
        }

        $empBonousModel = $this->model('EmpBonous');
        $bonuses = $empBonousModel->getAll();
        
        $employeeModel = $this->model('Employee');
        $employees = $employeeModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Bonus Management',
            'content' => 'admin/bonuses',
            'bonuses' => $bonuses,
            'employees' => $employees
        ]);
    }

    public function password_resets() {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Fetch all pending requests
        $query = "SELECT e.EmpID, e.FirstName, e.LastName, e.Email, d.DeptName as DeptName
                  FROM employee e
                  LEFT JOIN position p ON e.PositionID = p.PositionID
                  LEFT JOIN department d ON p.DeptID = d.DeptID
                  WHERE e.PasswordResetRequest = 1 AND e.Status = 'Active'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $pending_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('layouts/main', [
            'title' => 'Password Resets',
            'content' => 'admin/password_resets',
            'pending_requests' => $pending_requests
        ]);
    }

    public function reset_employee_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            $empId = $_POST['emp_id'] ?? null;
            $newPassword = $_POST['new_password'] ?? '';
            
            if ($empId && strlen($newPassword) >= 6) {
                $db = new Database();
                $conn = $db->getConnection();
                
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("UPDATE employee SET Password = :password, PasswordResetRequest = 0 WHERE EmpID = :id");
                $stmt->execute([
                    ':password' => $hashedPassword,
                    ':id' => $empId
                ]);

                // Send notification to the employee
                require_once __DIR__ . '/../models/Notification.php';
                $notifModel = new Notification();
                $notifModel->create($empId, "Your password has been successfully reset by an Administrator.", "info", "/employee/profile", "Password Reset Successful", 1);
                
                $_SESSION['reset_success'] = "Password reset successfully. Please share the new password with the employee.";
            } else {
                $_SESSION['reset_error'] = "Invalid input or password too short.";
            }
            
            $this->redirect('/payrollsystem/admin/password_resets');
        }
    }

    public function payroll() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            if ($_POST['action'] === 'generate') {
                $month = $_POST['month'];
                $year = $_POST['year'];
                $monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
                $payrollMonthStr = $monthNames[(int)$month] . ' ' . $year;
                
                $db = new Database();
                $conn = $db->getConnection();
                
                // Delete existing pending payrolls for this month
                $stmt = $conn->prepare("DELETE FROM payroll WHERE PayrollMonth = :pm AND Status = 'Pending'");
                $stmt->execute([':pm' => $payrollMonthStr]);
                
                // Get all active employees
                $stmt = $conn->query("SELECT e.*, p.BasicSalary FROM employee e JOIN position p ON e.PositionID = p.PositionID WHERE e.Status = 'Active'");
                $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
                $endDate = date("Y-m-t", strtotime($startDate));
                
                // Pre-fetch LeaveTypes for deduction rules
                $stmt = $conn->query("SELECT * FROM leavetypes");
                $leaveTypes = [];
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $lt) {
                    $leaveTypes[$lt['LeaveTypeID']] = $lt;
                }

                foreach ($employees as $emp) {
                    $empId = $emp['EmpID'];
                    $basicSalary = $emp['BasicSalary'];
                    
                    // Prorate salary based on JoinDate using Calendar Days Method
                    $daysInTargetMonth = (int)date('t', strtotime($startDate));
                    $payableDays = $daysInTargetMonth; // Default to full month
                    
                    $joinDateStr = $emp['JoinDate'] ?? null;
                    if ($joinDateStr) {
                        $joinTime = strtotime($joinDateStr);
                        $joinMonth = (int)date('n', $joinTime);
                        $joinYear = (int)date('Y', $joinTime);
                        
                        $targetMonthInt = (int)$month;
                        $targetYearInt = (int)$year;
                        
                        if ($targetYearInt < $joinYear || ($targetYearInt == $joinYear && $targetMonthInt < $joinMonth)) {
                            // Joined after this payroll month, skip entirely.
                            continue;
                        } elseif ($targetYearInt == $joinYear && $targetMonthInt == $joinMonth) {
                            $joinDay = (int)date('j', $joinTime);
                            $payableDays = $daysInTargetMonth - $joinDay + 1;
                            if ($payableDays < 0) $payableDays = 0;
                        }
                    }
                    
                    // Working Days, Daily Salary, Hourly Rate Calculation
                    $workingDaysCount = HolidayHelper::getWorkingDaysCountInMonth($year, $month, $emp['JoinDate'] ?? null);
                    $dailySalary = $workingDaysCount > 0 ? ($basicSalary / $workingDaysCount) : 0;
                    $hourlyRate = $dailySalary / 8;
                    
                    // Attendance Records, Absences & Dynamic Late Minutes
                    $stmt = $conn->prepare("
                        SELECT CheckInTime, CheckOutTime, Status 
                        FROM attendance 
                        WHERE EmpID = :emp AND AttendanceDate BETWEEN :sd AND :ed
                    ");
                    $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
                    $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $presentDays = 0;
                    $absentDays = 0;
                    $halfDays = 0;
                    $lateDays = 0;
                    $totalLateMinutes = 0;
                    
                    foreach ($attendanceRecords as $att) {
                        $st = $att['Status'];
                        if ($st === 'Present') {
                            $presentDays++;
                        } elseif (in_array($st, ['Absent', 'Full-Day Absence', 'Full-day absent'])) {
                            $absentDays++;
                        } elseif (in_array($st, ['Half Day', 'Half-Day Absence', 'Half-day absent'])) {
                            $halfDays++;
                        } elseif ($st === 'Late') {
                            $lateDays++;
                        }
                        
                        if (!empty($att['CheckInTime'])) {
                            $totalLateMinutes += Attendance::calculateLateMinutes($att['CheckInTime']);
                        }
                    }
                    
                    $lateHours = $totalLateMinutes / 60;
                    $lateDeduction = round($hourlyRate * $lateHours, 2);
                    $halfDayDeduction = round($halfDays * ($dailySalary * 0.5), 2);
                    $fullDayDeduction = round($absentDays * $dailySalary, 2);
                    $totalAttendanceDeduction = $lateDeduction + $halfDayDeduction + $fullDayDeduction;
                    
                    // Overtime stats
                    $stmt = $conn->prepare("SELECT SUM(TotalHours) as ot_hours, SUM(OTAmount) as ot_amount FROM overtimeassign WHERE EmpID = :emp AND OvertimeDate BETWEEN :sd AND :ed AND Status = 'Completed'");
                    $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
                    $otStats = $stmt->fetch(PDO::FETCH_ASSOC);
                    $otAmount = (float)($otStats['ot_amount'] ?: 0);
                    
                    // Bonus stats
                    $stmt = $conn->prepare("SELECT SUM(Amount) as bonus_amount FROM empbonous WHERE EmpID = :emp AND BonusDate BETWEEN :sd AND :ed");
                    $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
                    $bonusAmount = (float)($stmt->fetchColumn() ?: 0);
                    
                    // Leave Deduction Logic
                    $leaveDeductionAmount = 0;
                    $leaveDaysInMonth = 0;
                    
                    // Get all approved leaves intersecting this month
                    $stmt = $conn->prepare("
                        SELECT LeaveTypeID, StartDate, EndDate 
                        FROM leaverequest 
                        WHERE EmpID = :emp AND Status = 'Approved'
                        AND StartDate <= :ed AND EndDate >= :sd
                    ");
                    $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
                    $leavesThisMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $usedByThisMonthType = [];
                    foreach ($leavesThisMonth as $lr) {
                        // Calculate overlap days in this month
                        $lrStart = max(strtotime($startDate), strtotime($lr['StartDate']));
                        $lrEnd = min(strtotime($endDate), strtotime($lr['EndDate']));
                        $days = round(($lrEnd - $lrStart) / (60 * 60 * 24)) + 1;
                        if ($days > 0) {
                            $leaveDaysInMonth += $days;
                            $typeId = $lr['LeaveTypeID'];
                            if(!isset($usedByThisMonthType[$typeId])) $usedByThisMonthType[$typeId] = 0;
                            $usedByThisMonthType[$typeId] += $days;
                        }
                    }
                    
                    // For each type, check limit and calculate deduction
                    foreach($usedByThisMonthType as $typeId => $daysThisMonth) {
                        $lt = $leaveTypes[$typeId] ?? null;
                        if (!$lt) continue;
                        
                        $yearStart = "$year-01-01";
                        $priorMonthEnd = date("Y-m-d", strtotime($startDate . " -1 day"));
                        
                        $stmt = $conn->prepare("
                            SELECT SUM(DATEDIFF(LEAST(EndDate, :pme), GREATEST(StartDate, :ys)) + 1)
                            FROM leaverequest
                            WHERE EmpID = :emp AND LeaveTypeID = :lt AND Status = 'Approved'
                            AND StartDate <= :pme AND EndDate >= :ys
                        ");
                        $stmt->execute([':emp' => $empId, ':lt' => $typeId, ':pme' => $priorMonthEnd, ':ys' => $yearStart]);
                        $priorDays = $stmt->fetchColumn() ?: 0;
                        
                        // Check if it's strictly unpaid leave
                        if ($lt['IsPaid'] == 0) {
                            $leaveDeductionAmount += $daysThisMonth * $lt['DeductionRate'];
                            continue;
                        }

                        $limit = $lt['DaysAllowed'];
                        if ($limit >= 999) { // Unlimited paid leave, no deduction
                            continue; 
                        }
                        
                        $available = max(0, $limit - $priorDays);
                        $excessDays = max(0, $daysThisMonth - $available);
                        
                        if ($excessDays > 0) {
                            $deduction = $excessDays * $lt['DeductionRate'];
                            $leaveDeductionAmount += $deduction;
                        }
                    }
                    
                    // Net Salary Calculation
                    $totalDeductions = $totalAttendanceDeduction + $leaveDeductionAmount;
                    $grossSalary = $basicSalary + $otAmount + $bonusAmount;
                    $netSalary = max(0, $grossSalary - $totalDeductions);
                    
                    // Insert Payroll
                    $stmt = $conn->prepare("
                        INSERT INTO payroll (
                            EmpID, BasicSalary, PayrollMonth, PayableDays, BonousAmount, OvertimeAmount, 
                            LeaveDeductionAmount, NetSalary, Status, 
                            employee_code
                        ) VALUES (
                            :emp, :bs, :pm, :pd, :ba, :oa, 
                            :lda, :ns, 'Pending', 
                            :ec
                        )
                    ");
                    $stmt->execute([
                        ':emp' => $empId,
                        ':bs' => $basicSalary,
                        ':pm' => $payrollMonthStr,
                        ':pd' => $workingDaysCount,
                        ':ba' => $bonusAmount,
                        ':oa' => $otAmount,
                        ':lda' => $totalDeductions,
                        ':ns' => $netSalary,
                        ':ec' => str_pad($empId, 4, '0', STR_PAD_LEFT)
                    ]);
                }
                
                $_SESSION['payroll_success'] = "Payroll generated successfully for $payrollMonthStr.";
                $this->redirect("/payrollsystem/admin/payroll?month=$month&year=$year");
                return;
            } elseif ($_POST['action'] === 'pay') {
                $payrollId = $_POST['payroll_id'] ?? null;
                $month = $_POST['month'] ?? date('n');
                $year = $_POST['year'] ?? date('Y');
                
                if ($payrollId) {
                    $db = new Database();
                    $conn = $db->getConnection();
                    $stmt = $conn->prepare("UPDATE payroll SET Status = 'Paid' WHERE PayrollID = :id");
                    $stmt->execute([':id' => $payrollId]);
                    
                    // You could also store payment_method here if added to db later
                    $_SESSION['payroll_success'] = "Payment recorded successfully.";
                }
                
                $this->redirect("/payrollsystem/admin/payroll?month=$month&year=$year");
                return;
            }
        }
        $payrollModel = $this->model('Payroll');
        $employeeModel = $this->model('Employee');
        $employees = $employeeModel->getAll();

        $selectedMonth = $_GET['month'] ?? date('n');
        $selectedYear = $_GET['year'] ?? date('Y');
        $selectedEmpId = $_GET['emp_id'] ?? null;

        $payrolls = $payrollModel->getAll();
        
        $filteredPayrolls = [];
        if ($selectedMonth === 'yearly') {
            $grouped = [];
            foreach ($payrolls as $p) {
                if (strpos($p['PayrollMonth'], (string)$selectedYear) !== false) {
                    if ($selectedEmpId && $p['EmpID'] != $selectedEmpId) continue;
                    
                    $eid = $p['EmpID'];
                    if (!isset($grouped[$eid])) {
                        $grouped[$eid] = $p;
                        $grouped[$eid]['PayrollMonth'] = 'Yearly Total ' . $selectedYear;
                        $grouped[$eid]['BasicSalary'] = 0;
                        $grouped[$eid]['present_days'] = 0;
                        $grouped[$eid]['leave_days'] = 0;
                        $grouped[$eid]['absent_days'] = 0;
                        $grouped[$eid]['half_days'] = 0;
                        $grouped[$eid]['late_days'] = 0;
                        $grouped[$eid]['ot_hours'] = 0;
                        $grouped[$eid]['OvertimeAmount'] = 0;
                        $grouped[$eid]['BonousAmount'] = 0;
                        $grouped[$eid]['LeaveDeductionAmount'] = 0;
                        $grouped[$eid]['NetSalary'] = 0;
                        $grouped[$eid]['Status'] = 'N/A';
                    }
                    
                    $grouped[$eid]['BasicSalary'] += $p['BasicSalary'];
                    $grouped[$eid]['present_days'] += $p['present_days'];
                    $grouped[$eid]['leave_days'] += $p['leave_days'];
                    $grouped[$eid]['absent_days'] += $p['absent_days'];
                    $grouped[$eid]['half_days'] += $p['half_days'];
                    $grouped[$eid]['late_days'] += $p['late_days'];
                    $grouped[$eid]['ot_hours'] += $p['ot_hours'];
                    $grouped[$eid]['OvertimeAmount'] += $p['OvertimeAmount'];
                    $grouped[$eid]['BonousAmount'] += $p['BonousAmount'];
                    $grouped[$eid]['LeaveDeductionAmount'] += $p['LeaveDeductionAmount'];
                    $grouped[$eid]['NetSalary'] += $p['NetSalary'];
                }
            }
            $filteredPayrolls = array_values($grouped);
        } elseif ($selectedMonth === 'all') {
            foreach ($payrolls as $p) {
                if (strpos($p['PayrollMonth'], (string)$selectedYear) !== false) {
                    if ($selectedEmpId) {
                        if ($p['EmpID'] == $selectedEmpId) {
                            $filteredPayrolls[] = $p;
                        }
                    } else {
                        $filteredPayrolls[] = $p;
                    }
                }
            }
            
            // Sort by month (Jan to Dec), then by employee ID
            usort($filteredPayrolls, function($a, $b) {
                $timeA = strtotime($a['PayrollMonth']);
                $timeB = strtotime($b['PayrollMonth']);
                if ($timeA == $timeB) {
                    return $a['EmpID'] <=> $b['EmpID'];
                }
                return $timeA <=> $timeB;
            });
        } else {
            $monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
            $targetMonthStr = $monthNames[(int)$selectedMonth] . ' ' . $selectedYear;
            
            foreach ($payrolls as $p) {
                if ($p['PayrollMonth'] === $targetMonthStr) {
                    if ($selectedEmpId) {
                        if ($p['EmpID'] == $selectedEmpId) {
                            $filteredPayrolls[] = $p;
                        }
                    } else {
                        $filteredPayrolls[] = $p;
                    }
                }
            }
        }
        $payrolls = $filteredPayrolls;
        $viewMode = 'month_view';

        $selectedEmpName = '';
        if ($selectedEmpId) {
            foreach($employees as $e) {
                if ($e['EmpID'] == $selectedEmpId) {
                    $selectedEmpName = $e['FirstName'] . ' ' . $e['LastName'];
                    break;
                }
            }
        }

        $this->view('layouts/main', [
            'title' => 'Monthly Payroll',
            'content' => 'admin/payroll',
            'payrolls' => $payrolls,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'employees' => $employees,
            'selectedEmpId' => $selectedEmpId,
            'viewMode' => $viewMode,
            'selectedEmpName' => $selectedEmpName ?? ''
        ]);
    }
    
    public function payroll_slip($id = null) {
        if (!$id) {
            $this->redirect('/payrollsystem/admin/payroll');
            return;
        }
        
        $payrollModel = $this->model('Payroll');
        $payrollData = $payrollModel->getById($id);
        
        if (!$payrollData) {
            $this->redirect('/payrollsystem/admin/payroll');
            return;
        }

        // We don't use layouts/main because it's a print view
        $this->view('admin/payroll_slip', [
            'title' => 'Payroll Slip',
            'payroll' => $payrollData
        ]);
    }

    public function rules() {
        $this->view('layouts/main', [
            'title' => 'Company Rules & Policies',
            'content' => 'employee/rules'
        ]);
    }
}
