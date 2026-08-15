<?php
class EmployeeController extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
            $this->redirect('/payrollsystem/auth/login');
        }
    }

    public function index() {
        $emp_id = $_SESSION['employee_id'];
        
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        $payrollModel = $this->model('Payroll');
        
        $employee = $employeeModel->getEmployeeById($emp_id);
        
        $today = date('Y-m-d');
        $todayRecord = $attendanceModel->getTodayRecord($emp_id, $today);
        $recentAttendance = $attendanceModel->getEmployeeRecords($emp_id);

        $recentPayrolls = $payrollModel->getByEmployee($emp_id);
        
        $overtimeModel = $this->model('OvertimeAssign');
        $upcomingOvertime = $overtimeModel->getUpcomingByEmployee($emp_id);

        $this->view('layouts/main', [
            'title' => 'Employee Dashboard',
            'content' => 'employee/dashboard',
            'employee' => $employee,
            'todayRecord' => $todayRecord,
            'recentAttendance' => array_slice($recentAttendance, 0, 5),
            'recentPayrolls' => array_slice($recentPayrolls, 0, 5),
            'upcomingOvertime' => array_slice($upcomingOvertime, 0, 5),
            'is_working_day' => HolidayHelper::isWorkingDay($today)
        ]);
    }

    public function attendance() {
        $emp_id = $_SESSION['employee_id'];
        $attendanceModel = $this->model('Attendance');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $today = date('Y-m-d');
            $time = date('H:i:s');
            
            if ($_POST['action'] === 'check_in') {
                if (!HolidayHelper::isWorkingDay($today)) {
                    $_SESSION['att_error'] = 'Attendance recording is disabled on non-working days.';
                } elseif ($time < '08:30:00' || $time > '17:00:00') {
                    $_SESSION['att_error'] = 'Check-in is only allowed between 8:30 AM and 5:00 PM.';
                } else {
                    $attendanceModel->checkIn($emp_id, $time, $today);
                    $_SESSION['att_success'] = 'Checked in successfully.';
                }
            } elseif ($_POST['action'] === 'check_out') {
                if (!HolidayHelper::isWorkingDay($today)) {
                    $_SESSION['att_error'] = 'Attendance recording is disabled on non-working days.';
                } else {
                    $attendanceModel->checkOut($emp_id, $time, $today);
                    $_SESSION['att_success'] = 'Checked out successfully.';
                }
            }
            $this->redirect('/payrollsystem/employee');
        }
        
        $records = $attendanceModel->getEmployeeRecords($emp_id);

        $overtimeModel = $this->model('OvertimeAssign');
        $overtimes = $overtimeModel->getByEmployee($emp_id);
        
        $otMap = [];
        foreach ($overtimes as $ot) {
            $otMap[$ot['OvertimeDate']] = $ot['OvertimeHours'];
        }

        foreach ($records as &$record) {
            $working_hours = 0;
            if (!empty($record['CheckInTime']) && !empty($record['CheckOutTime'])) {
                $in = strtotime($record['CheckInTime']);
                $out = strtotime($record['CheckOutTime']);
                $working_hours = round(abs($out - $in) / 3600, 1);
            }
            $record['working_hours'] = $working_hours;
            $record['ot_hours'] = $otMap[$record['AttendanceDate']] ?? 0;
        }
        unset($record);

        $this->view('layouts/main', [
            'title' => 'My Attendance',
            'content' => 'employee/attendance',
            'myAttendance' => $records,
            'myCorrections' => [],
            'is_working_day' => HolidayHelper::isWorkingDay(date('Y-m-d'))
        ]);
    }

    public function ot_action() {
        $emp_id = $_SESSION['employee_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $ot_id = $_POST['ot_id'];
            $response = $_POST['response'] ?? '';
            $status = $_POST['action'] === 'accept' ? 'Accepted' : 'Rejected';
            
            $overtimeModel = $this->model('OvertimeAssign');
            $overtimeModel->acceptReject($ot_id, $emp_id, $status, $response);
            
            $_SESSION['flash_success'] = "Overtime assignment " . strtolower($status) . ".";
        }
        $this->redirect('/payrollsystem/employee');
    }

    public function ot_attendance() {
        $emp_id = $_SESSION['employee_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $ot_id = $_POST['ot_id'];
            $overtimeModel = $this->model('OvertimeAssign');
            $ot = $overtimeModel->getById($ot_id);
            
            if ($ot && $ot['EmpID'] == $emp_id) {
                $time = date('Y-m-d H:i:s');
                if ($_POST['action'] === 'check_in') {
                    $overtimeModel->otCheckIn($ot_id, $emp_id, $time);
                    $_SESSION['flash_success'] = "Overtime checked in.";
                } elseif ($_POST['action'] === 'check_out') {
                    // Calculate actual hours
                    $inTime = strtotime($ot['OTCheckIn']);
                    $outTime = time();
                    $actualHours = round(($outTime - $inTime) / 3600, 2);
                    $overtimeModel->otCheckOut($ot_id, $emp_id, $time, $actualHours);
                    $_SESSION['flash_success'] = "Overtime checked out. Total: {$actualHours} Hrs.";
                }
            }
        }
        $this->redirect('/payrollsystem/employee');
    }

    public function leaves() {
        $emp_id = $_SESSION['employee_id'];
        $leaveRequestModel = $this->model('LeaveRequest');
        $leaveTypeModel = $this->model('LeaveType');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if ($_POST['action'] === 'apply') {
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                
                // Validate against attendance
                $db = new Database();
                $conn = $db->getConnection();
                $aQuery = "SELECT * FROM attendance 
                           WHERE EmpID = :emp_id 
                           AND AttendanceDate >= :sd 
                           AND AttendanceDate <= :ed
                           AND CheckInTime IS NOT NULL";
                $aStmt = $conn->prepare($aQuery);
                $aStmt->execute([':emp_id' => $emp_id, ':sd' => $start_date, ':ed' => $end_date]);
                
                if ($aStmt->rowCount() > 0) {
                    $_SESSION['leave_error'] = "Leave request failed: You have already checked in for this date.";
                    $this->redirect('/payrollsystem/employee/leaves');
                    return;
                }

                $leaveRequestModel->LeaveTypeID = $_POST['leave_type_id'];
                $leaveRequestModel->EmpID = $emp_id;
                $leaveRequestModel->StartDate = $start_date;
                $leaveRequestModel->EndDate = $end_date;
                $leaveRequestModel->Reason = $_POST['reason'];
                $leaveRequestModel->Status = 'Pending';
                $leaveRequestModel->create();
                $_SESSION['leave_success'] = "Leave application submitted successfully.";
                $this->redirect('/payrollsystem/employee/leaves');
                return;
            }
        }

        $leaveRequests = $leaveRequestModel->getByEmployee($emp_id);
        $leaveTypes = $leaveTypeModel->getAll();

        $employee = $this->model('Employee')->getEmployeeById($emp_id);
        $joinDate = new DateTime($employee['JoinDate']);
        $currentDate = new DateTime();
        $diff = $currentDate->diff($joinDate);
        $workedMonths = ($diff->y * 12) + $diff->m;

        $leaveBalances = [];
        foreach ($leaveTypes as $type) {
            $used = $leaveRequestModel->getUsedDays($emp_id, $type['LeaveTypeID']);
            
            // Check if employee has worked long enough for this leave type
            $is_eligible = $workedMonths >= $type['DurationMonths'];
            $ineligible_reason = '';
            if (!$is_eligible) {
                $ineligible_reason = "Requires " . $type['DurationMonths'] . " months of employment.";
            }
            
            $leaveBalances[] = [
                'LeaveTypeID' => $type['LeaveTypeID'],
                'LeaveType' => $type['LeaveType'],
                'DaysAllowed' => $type['DaysAllowed'],
                'is_paid' => $type['IsPaid'],
                'used' => $used,
                'is_eligible' => $is_eligible,
                'ineligible_reason' => $ineligible_reason
            ];
        }

        $this->view('layouts/main', [
            'title' => 'My Leaves',
            'content' => 'employee/leaves',
            'leaveRequests' => $leaveRequests,
            'leaveTypes' => $leaveTypes,
            'leaveBalances' => $leaveBalances,
            'hasClockedInToday' => false // Default to false, or check attendance model
        ]);
    }

    public function payroll_slip($id = null) {
        if (!$id) {
            $this->redirect('/payrollsystem/employee');
            return;
        }
        
        $payrollModel = $this->model('Payroll');
        $payrollData = $payrollModel->getById($id);
        
        // Security check: ensure the slip belongs to this employee
        if (!$payrollData || $payrollData['EmpID'] != $_SESSION['employee_id']) {
            $this->redirect('/payrollsystem/employee');
            return;
        }

        $this->view('admin/payroll_slip', [
            'title' => 'My Payroll Slip',
            'payroll' => $payrollData
        ]);
    }
}
