<?php
class EmployeeController extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
            $this->redirect('/payrollsystem/auth/login');
        }

        $url = isset($_GET['url']) ? explode('/', rtrim($_GET['url'], '/')) : [];
        $method = $url[1] ?? 'index';

        if (isset($_SESSION['is_first_login']) && $_SESSION['is_first_login'] == 1) {
            if ($method !== 'first_login' && $method !== 'changeFirstPassword') {
                $this->redirect('/payrollsystem/employee/first_login');
            }
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

    public function overtime() {
        $emp_id = $_SESSION['employee_id'];
        $overtimeModel = $this->model('OvertimeAssign');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['id'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $ot_id = $_POST['id'];
            $action = $_POST['action'];
            
            if ($action === 'accept') {
                $overtimeModel->accept($ot_id, $emp_id);
                $_SESSION['flash_success'] = 'Overtime assignment accepted.';
            } elseif ($action === 'reject') {
                $overtimeModel->reject($ot_id, $emp_id);
                $_SESSION['flash_success'] = 'Overtime assignment rejected.';
            } elseif ($action === 'checkin') {
                if ($overtimeModel->checkIn($ot_id, $emp_id)) {
                    $_SESSION['flash_success'] = 'Checked in successfully for overtime.';
                } else {
                    $_SESSION['flash_error'] = 'Cannot check in yet. Please try again within 10 minutes of your scheduled start time.';
                }
            }
            header("Location: /payrollsystem/employee/overtime");
            exit;
        }

        $overtimes = $overtimeModel->getByEmployee($emp_id);

        $upcoming = 0;
        $totalHours = 0;
        $totalEarnings = 0;
        $pending = 0;
        $today = date('Y-m-d');

        foreach ($overtimes as $ot) {
            if ($ot['OvertimeDate'] >= $today && in_array($ot['Status'], ['Accepted', 'Completed', 'InProgress'])) {
                $upcoming++;
            }
            if ($ot['Status'] === 'Pending') {
                $pending++;
            }
            if ($ot['Status'] === 'Completed') {
                $totalHours += (float)$ot['TotalHours'];
                $totalEarnings += (float)$ot['OTAmount'];
            }
        }

        $this->view('layouts/main', [
            'title' => 'My Overtime',
            'content' => 'employee/overtime',
            'overtimes' => $overtimes,
            'upcoming' => $upcoming,
            'totalHours' => round($totalHours, 1),
            'totalEarnings' => round($totalEarnings, 2),
            'pending' => $pending
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
                $leaveRequestModel = $this->model('LeaveRequest');
                if ($leaveRequestModel->isOnApprovedLeave($emp_id, $today)) {
                    $_SESSION['att_error'] = 'You cannot check in while on an approved leave.';
                } elseif (!HolidayHelper::isWorkingDay($today)) {
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
            $otMap[$ot['OvertimeDate']] = $ot['TotalHours'];
        }

        foreach ($records as &$record) {
            $working_hours = 0;
            if (!empty($record['CheckInTime']) && !empty($record['CheckOutTime'])) {
                $in = strtotime($record['CheckInTime']);
                $out = strtotime($record['CheckOutTime']);
                $working_hours = round(abs($out - $in) / 3600, 1);
            } elseif (!empty($record['CheckInTime']) && empty($record['CheckOutTime'])) {
                $in = strtotime($record['CheckInTime']);
                $now = strtotime(date('H:i:s'));
                $working_hours = round(abs($now - $in) / 3600, 1);
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



    public function leaves() {
        $emp_id = $_SESSION['employee_id'];
        $leaveRequestModel = $this->model('LeaveRequest');
        $leaveTypeModel = $this->model('LeaveType');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if ($_POST['action'] === 'apply') {
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];

                if ($start_date <= date('Y-m-d')) {
                    $_SESSION['leave_error'] = "Leave request failed: Leave must be requested at least one day in advance.";
                    $this->redirect('/payrollsystem/employee/leaves');
                    return;
                }
                
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

                $notifModel = $this->model('Notification');
                $employeeName = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
                $msg = "{$employeeName} submitted a new leave request from {$start_date} to {$end_date}.";
                $notifModel->create(1, $msg, 'info', '/admin/leaves', 'New Leave Request', $emp_id);

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

    public function salary_history() {
        $emp_id = $_SESSION['employee_id'];
        $payrollModel = $this->model('Payroll');
        $payrolls = $payrollModel->getByEmployee($emp_id);
        
        $this->view('layouts/main', [
            'title' => 'My Salary History',
            'content' => 'employee/salary_history',
            'payrolls' => $payrolls
        ]);
    }

    public function profile() {
        $emp_id = $_SESSION['employee_id'];
        $employee = $this->model('Employee')->getEmployeeById($emp_id);
        
        $this->view('layouts/main', [
            'title' => 'My Profile',
            'content' => 'employee/profile',
            'employee' => $employee
        ]);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            $emp_id = $_SESSION['employee_id'];
            $employeeModel = $this->model('Employee');
            $employee = $employeeModel->getEmployeeById($emp_id);

            $phone = trim($_POST['phone_number']);
            if (!preg_match('/^[0-9\-\+\s\(\)]{7,20}$/', $phone)) {
                $_SESSION['profile_error'] = 'Invalid phone number format.';
                $this->redirect('/payrollsystem/employee/profile');
                return;
            }

            $employeeModel->EmpID = $emp_id;
            $employeeModel->FirstName = trim($_POST['first_name']);
            $employeeModel->LastName = trim($_POST['last_name']);
            $employeeModel->Email = $employee['Email']; // Email is immutable
            $employeeModel->PhoneNumber = $phone;
            $employeeModel->Address = trim($_POST['address']);
            
            // Retain fields
            $employeeModel->Gender = $employee['Gender'];
            $employeeModel->PositionID = $employee['PositionID'];
            $employeeModel->JoinDate = $employee['JoinDate'];
            $employeeModel->Status = $employee['Status'];
            
            if ($employeeModel->update()) {
                $_SESSION['first_name'] = $employeeModel->FirstName;
                $_SESSION['last_name'] = $employeeModel->LastName;
                $_SESSION['email'] = $employeeModel->Email;
                $_SESSION['profile_success'] = 'Profile updated successfully.';
            } else {
                $_SESSION['profile_error'] = 'Failed to update profile.';
            }
        }
        $this->redirect('/payrollsystem/employee/profile');
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            $emp_id = $_SESSION['employee_id'];
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (strlen($new_password) < 6) {
                $_SESSION['profile_error'] = 'New password must be at least 6 characters.';
                $this->redirect('/payrollsystem/employee/profile');
                return;
            }

            if ($new_password !== $confirm_password) {
                $_SESSION['profile_error'] = 'New passwords do not match.';
                $this->redirect('/payrollsystem/employee/profile');
                return;
            }

            $employeeModel = $this->model('Employee');
            $employee = $employeeModel->getEmployeeById($emp_id);

            if (!password_verify($current_password, $employee['Password'])) {
                $_SESSION['profile_error'] = 'Incorrect current password.';
                $this->redirect('/payrollsystem/employee/profile');
                return;
            }

            // Update only password
            $db = new Database();
            $conn = $db->getConnection();
            $query = "UPDATE employee SET Password = :pwd WHERE EmpID = :id";
            $stmt = $conn->prepare($query);
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bindParam(':pwd', $hash);
            $stmt->bindParam(':id', $emp_id);
            
            if ($stmt->execute()) {
                $_SESSION['profile_success'] = 'Password changed successfully.';
            } else {
                $_SESSION['profile_error'] = 'Failed to change password.';
            }
        }
        $this->redirect('/payrollsystem/employee/profile');
    }

    public function updatePhoto() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $emp_id = $_SESSION['employee_id'];
            $file = $_FILES['profile_photo'];

            if ($file['error'] === 0) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($ext), $allowed)) {
                    $newFileName = 'emp_' . $emp_id . '_' . time() . '.' . $ext;
                    $uploadPath = dirname(__DIR__) . '/assets/uploads/profiles/' . $newFileName;
                    
                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $db = new Database();
                        $conn = $db->getConnection();
                        $query = "UPDATE employee SET ProfilePhoto = :photo WHERE EmpID = :id";
                        $stmt = $conn->prepare($query);
                        $photoUrl = 'assets/uploads/profiles/' . $newFileName;
                        $stmt->bindParam(':photo', $photoUrl);
                        $stmt->bindParam(':id', $emp_id);
                        $stmt->execute();
                        
                        $_SESSION['profile_success'] = 'Profile photo updated.';
                    } else {
                        $_SESSION['profile_error'] = 'Failed to upload photo.';
                    }
                } else {
                    $_SESSION['profile_error'] = 'Invalid file type. Only JPG, PNG, GIF are allowed.';
                }
            } else {
                $_SESSION['profile_error'] = 'Error uploading file.';
            }
        }
        $this->redirect('/payrollsystem/employee/profile');
    }
    public function first_login() {
        if (!isset($_SESSION['is_first_login']) || $_SESSION['is_first_login'] != 1) {
            $this->redirect('/payrollsystem/employee');
        }
        
        $this->view('employee/first_login', ['title' => 'Change Default Password']);
    }

    public function changeFirstPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            $emp_id = $_SESSION['employee_id'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (strlen($new_password) < 6) {
                $this->view('employee/first_login', ['title' => 'Change Default Password', 'error' => 'New password must be at least 6 characters.']);
                return;
            }

            if ($new_password !== $confirm_password) {
                $this->view('employee/first_login', ['title' => 'Change Default Password', 'error' => 'New passwords do not match.']);
                return;
            }

            $db = new Database();
            $conn = $db->getConnection();
            $query = "UPDATE employee SET Password = :pwd, is_first_login = 0 WHERE EmpID = :id";
            $stmt = $conn->prepare($query);
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bindParam(':pwd', $hash);
            $stmt->bindParam(':id', $emp_id);
            
            if ($stmt->execute()) {
                $_SESSION['is_first_login'] = 0;
                $_SESSION['flash_success'] = 'Password changed successfully. Welcome to your dashboard!';
                $this->redirect('/payrollsystem/employee');
            } else {
                $this->view('employee/first_login', ['title' => 'Change Default Password', 'error' => 'Failed to change password.']);
            }
        } else {
            $this->redirect('/payrollsystem/employee/first_login');
        }
    }
}
