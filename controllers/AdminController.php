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
        $payrollModel = $this->model('Payroll');
        $bonousModel = $this->model('EmpBonous');

        $employees = $employeeModel->getAll();
        $totalEmployees = count($employees);
        $activeEmployees = count(array_filter($employees, function($e) { return $e['Status'] == 'Active'; }));

        $attendance = $attendanceModel->getAllRecords();
        $today = date('Y-m-d');
        $presentToday = count(array_filter($attendance, function($a) use ($today) { return $a['AttendanceDate'] == $today && $a['Status'] == 'Present'; }));
        $lateToday = count(array_filter($attendance, function($a) use ($today) { return $a['AttendanceDate'] == $today && $a['Status'] == 'Late'; }));
        $absentToday = count(array_filter($attendance, function($a) use ($today) { return $a['AttendanceDate'] == $today && $a['Status'] == 'Absent'; }));
        
        $recentAttendance = array_slice($attendance, 0, 5);

        $this->view('layouts/main', [
            'title' => 'Admin Dashboard',
            'content' => 'admin/dashboard',
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'presentToday' => $presentToday,
            'lateToday' => $lateToday,
            'absentToday' => $absentToday,
            'recentAttendance' => $recentAttendance
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
                    $departmentModel->delete($_POST['id']);
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
                    $positionModel->delete($_POST['id']);
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
                    $employeeModel->FirstName = $_POST['first_name'];
                    $employeeModel->LastName = $_POST['last_name'];
                    $employeeModel->Gender = $_POST['gender'] ?? 'Other';
                    $employeeModel->Email = $_POST['email'];
                    $employeeModel->Password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $employeeModel->PhoneNumber = $_POST['phone'];
                    $employeeModel->Address = $_POST['address'];
                    $employeeModel->PositionID = $_POST['position_id'];
                    $employeeModel->JoinDate = $_POST['join_date'];
                    $employeeModel->Status = 'Active';
                    
                    $employeeModel->create();
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
                
                $employeeModel->EmpID = $id;
                $employeeModel->FirstName = $_POST['first_name'];
                $employeeModel->LastName = $_POST['last_name'];
                $employeeModel->Gender = $_POST['gender'];
                $employeeModel->Email = $_POST['email'];
                $employeeModel->JoinDate = $_POST['join_date'];
                $employeeModel->PhoneNumber = $_POST['phone'];
                $employeeModel->Address = $_POST['address'];
                $employeeModel->PositionID = $_POST['position_id'];
                $employeeModel->Status = $_POST['status'];
                
                if (!empty($_POST['password'])) {
                    $employeeModel->Password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $employeeModel->update();
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
            $otMap[$ot['EmpID'] . '_' . $ot['OvertimeDate']] = $ot['OvertimeHours'];
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
            if ($record['CheckInTime'] && $record['CheckOutTime']) {
                $in = strtotime($record['CheckInTime']);
                $out = strtotime($record['CheckOutTime']);
                $working_hours = round(abs($out - $in) / 3600, 1);
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
                'is_auto_checkout' => 0, 
                'working_hours' => $working_hours,
                'ot_hours' => $ot_hours, 
                'status' => $record['Status'],
                'late_minutes' => 0
            ];
        }
        
        $filtered = array_filter($data, function($item) {
            $match = true;
            if (!empty($_GET['date_start']) && $item['date'] < $_GET['date_start']) $match = false;
            if (!empty($_GET['date_end']) && $item['date'] > $_GET['date_end']) $match = false;
            if (!empty($_GET['department_id']) && $item['department_id'] != $_GET['department_id']) $match = false;
            if (!empty($_GET['employee_id']) && $item['employee_id'] != $_GET['employee_id']) $match = false;
            if (!empty($_GET['status']) && $item['status'] != $_GET['status']) $match = false;
            
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
            $this->redirect('/payrollsystem/admin/leaves');
        }

        $filters = [
            'search' => $_GET['search'] ?? '',
            'DeptID' => $_GET['department_id'] ?? '',
            'status' => $_GET['status'] ?? '',
            'leave_type' => $_GET['leave_type'] ?? '',
            'date' => $_GET['date'] ?? ''
        ];

        // Currently we just pass filters to view, the model filtering can be implemented later
        $leaveRequests = $leaveRequestModel->getAll();
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
                    $leaveTypeModel->delete($_POST['id']);
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
                    $rate = (float)$_POST['rate'];
                    
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
                    
                    $holidayModel = $this->model('Holiday');
                    $isHoliday = $holidayModel->isHoliday($otDate);
                    $dayOfWeek = date('N', strtotime($otDate));
                    $isWeekend = ($dayOfWeek >= 6);
                    $isWorkingDay = (!$isHoliday && !$isWeekend);
                    
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
                            $this->redirect('/payrollsystem/admin/overtime_assignments?error=' . urlencode('Overtime assignment failed: Invalid overtime schedule.'));
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
                            $exStart = strtotime("1970-01-01 {$ex['StartTime']}");
                            $exEnd = strtotime("1970-01-01 {$ex['EndTime']}");
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
                        $overtimeModel->EmpID = $empId;
                        $overtimeModel->OvertimeDate = $otDate;
                        $overtimeModel->StartTime = $startTime;
                        $overtimeModel->EndTime = $endTime;
                        $overtimeModel->OvertimeHours = $hours;
                        $overtimeModel->OTRate = $rate;
                        $overtimeModel->OTAmount = $hours * $rate;
                        
                        if ($_POST['action'] === 'add') {
                            $overtimeModel->create();
                        } else {
                            $overtimeModel->OvertimeID = $_POST['id'];
                            $overtimeModel->update();
                        }
                    }
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

    public function overtime() {
        $overtimeModel = $this->model('OvertimeAssign');

        $assignments = $overtimeModel->getAll();

        $this->view('layouts/main', [
            'title' => 'Overtime Management',
            'content' => 'admin/overtime',
            'assignments' => $assignments
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
                    $type = $_POST['type']; // This is a string from the UI select
                    
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
                    $dailySalary = $basicSalary / 30;
                    
                    // Attendance stats
                    $stmt = $conn->prepare("
                        SELECT 
                            SUM(CASE WHEN CheckInTime IS NOT NULL THEN 1 ELSE 0 END) as present_days,
                            SUM(CASE WHEN Status = 'Full-Day Absence' THEN 1 ELSE 0 END) as absent_days,
                            SUM(CASE WHEN Status = 'Half-Day Absence' THEN 1 ELSE 0 END) as half_days
                        FROM attendance 
                        WHERE EmpID = :emp AND AttendanceDate BETWEEN :sd AND :ed
                    ");
                    $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
                    $attStats = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Overtime stats
                    $stmt = $conn->prepare("SELECT SUM(OvertimeHours) as ot_hours, SUM(OTAmount) as ot_amount FROM overtimeassign WHERE EmpID = :emp AND OvertimeDate BETWEEN :sd AND :ed");
                    $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
                    $otStats = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Bonus stats
                    $stmt = $conn->prepare("SELECT SUM(Amount) as bonus_amount FROM empbonous WHERE EmpID = :emp AND BonusDate BETWEEN :sd AND :ed");
                    $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
                    $bonusAmount = $stmt->fetchColumn() ?: 0;
                    
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
                        $lt = $leaveTypes[$typeId];
                        
                        // We also need total days used in the year BEFORE this month to know if limit is already exceeded
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
                            // All days taken are deducted at the DeductionRate
                            $leaveDeductionAmount += $daysThisMonth * $lt['DeductionRate'];
                            continue;
                        }

                        $limit = $lt['DaysAllowed'];
                        if ($limit >= 999) { // Unlimited paid leave, no deduction
                            continue; 
                        }
                        
                        // How many days available coming into this month?
                        $available = max(0, $limit - $priorDays);
                        $excessDays = max(0, $daysThisMonth - $available);
                        
                        if ($excessDays > 0) {
                            // Treat DeductionRate as a flat currency amount per excess day
                            $deduction = $excessDays * $lt['DeductionRate'];
                            $leaveDeductionAmount += $deduction;
                        }
                    }
                    
                    $grossSalary = $basicSalary + ($otStats['ot_amount'] ?: 0) + $bonusAmount;
                    $netSalary = $grossSalary - $leaveDeductionAmount;
                    
                    // Insert Payroll
                    $stmt = $conn->prepare("
                        INSERT INTO payroll (
                            EmpID, BasicSalary, PayrollMonth, BonousAmount, OvertimeAmount, 
                            LeaveDeductionAmount, NetSalary, Status, 
                            employee_code, present_days, leave_days, absent_days, half_days, 
                            ot_hours
                        ) VALUES (
                            :emp, :bs, :pm, :ba, :oa, 
                            :lda, :ns, 'Pending', 
                            :ec, :pd, :ld, :ad, :hd, 
                            :oth
                        )
                    ");
                    $stmt->execute([
                        ':emp' => $empId,
                        ':bs' => $basicSalary,
                        ':pm' => $payrollMonthStr,
                        ':ba' => $bonusAmount,
                        ':oa' => $otStats['ot_amount'] ?: 0,
                        ':lda' => $leaveDeductionAmount,
                        ':ns' => $netSalary,
                        ':ec' => str_pad($empId, 4, '0', STR_PAD_LEFT),
                        ':pd' => $attStats['present_days'] ?: 0,
                        ':ld' => $leaveDaysInMonth,
                        ':ad' => $attStats['absent_days'] ?: 0,
                        ':hd' => $attStats['half_days'] ?: 0,
                        ':oth' => $otStats['ot_hours'] ?: 0
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
        $payrolls = $payrollModel->getAll();

        $selectedMonth = $_GET['month'] ?? date('n');
        $selectedYear = $_GET['year'] ?? date('Y');

        $this->view('layouts/main', [
            'title' => 'Monthly Payroll',
            'content' => 'admin/payroll',
            'payrolls' => $payrolls,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear
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
}
