<?php
class EmployeeController extends Controller {
    
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
            $this->redirect('/payrollsystem/auth/login');
            return;
        }
        
        // Auto-checkout if missed
        require_once __DIR__ . '/../models/Employee.php';
        require_once __DIR__ . '/../models/Attendance.php';
        $employeeModel = new Employee();
        $employee = $employeeModel->getByUserId($_SESSION['user_id']);
        if ($employee) {
            $attendanceModel = new Attendance();
            $attendanceModel->autoCheckoutIfMissed($employee['id']);
        }
    }
    
    public function index() {
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        
        $employee = $employeeModel->getByUserId($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            if ($_POST['action'] === 'clock_in') {
                if ($employee['status'] !== 'Active') {
                    $_SESSION['att_error'] = "Inactive employees cannot check in.";
                } else {
                    $date = date('Y-m-d');
                    $time = date('H:i:s');
                    
                    $holidayModel = $this->model('Holiday');
                    if ($holidayModel->isHoliday($date) || date('N', strtotime($date)) >= 6) {
                        $_SESSION['att_error'] = "Attendance submission is not allowed on weekends or public holidays.";
                    } else {
                        $db = new Database();
                        $conn = $db->getConnection();
                        $leaveQuery = "SELECT id FROM leave_requests WHERE employee_id = :emp_id AND start_date <= :date AND end_date >= :date AND status = 'Approved'";
                        $leaveStmt = $conn->prepare($leaveQuery);
                        $leaveStmt->bindParam(':emp_id', $employee['id']);
                        $leaveStmt->bindParam(':date', $date);
                        $leaveStmt->execute();
                        
                        if ($leaveStmt->rowCount() > 0) {
                            $_SESSION['att_error'] = "You are on approved leave today. Check-in is not allowed.";
                        } else {
                            if (strtotime($time) < strtotime('08:30:00')) {
                                $_SESSION['att_error'] = "Check-in is not allowed before 8:30 AM.";
                            } elseif (strtotime($time) > strtotime('17:00:00')) {
                                $_SESSION['att_error'] = "Check-in is not allowed after 5:00 PM.";
                            } else {
                                $todayAtt = $attendanceModel->getToday($employee['id']);
                                if ($todayAtt) {
                                    $_SESSION['att_error'] = "You have already checked in today.";
                                } else {
                                    $attendanceModel->clockIn($employee['id']);
                                    $_SESSION['att_success'] = "Checked in successfully.";
                                }
                            }
                        }
                    }
                }
            } elseif ($_POST['action'] === 'clock_out') {
                $todayAtt = $attendanceModel->getToday($employee['id']);
                if ($todayAtt && !$todayAtt['check_out']) {
                    $attendanceModel->clockOut($todayAtt['id'], $todayAtt['check_in']);
                    $_SESSION['att_success'] = "Checked out successfully.";
                } else {
                    $_SESSION['att_error'] = "You cannot check out without a valid check-in.";
                }
            }
            $this->redirect('/payrollsystem/employee'); 
        }

        $todayAttendance = $attendanceModel->getToday($employee['id']);

        $this->view('layouts/employee', [
            'title' => 'Employee Dashboard',
            'content' => 'employee/dashboard',
            'employee' => $employee,
            'todayAttendance' => $todayAttendance
        ]);
    }

    public function profile() {
        $employeeModel = $this->model('Employee');
        $employee = $employeeModel->getByUserId($_SESSION['user_id']);

        $this->view('layouts/employee', [
            'title' => 'My Profile',
            'content' => 'employee/profile',
            'employee' => $employee
        ]);
    }

    public function attendance() {
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        
        $employee = $employeeModel->getByUserId($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'correction') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $att_id = $_POST['attendance_id'];
            $req_in = !empty($_POST['corrected_check_in']) ? $_POST['corrected_check_in'] : null;
            $req_out = !empty($_POST['corrected_check_out']) ? $_POST['corrected_check_out'] : null;
            $reason = $_POST['reason'];

            // Save correction request
            $query = "INSERT INTO attendance_corrections SET attendance_id=:att_id, employee_id=:emp_id, corrected_check_in=:req_in, corrected_check_out=:req_out, reason=:reason, status='Pending'";
            
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':att_id', $att_id);
            $stmt->bindParam(':emp_id', $employee['id']);
            $stmt->bindParam(':req_in', $req_in);
            $stmt->bindParam(':req_out', $req_out);
            $stmt->bindParam(':reason', $reason);
            $stmt->execute();

            $this->redirect('/payrollsystem/employee/attendance');
        }

        $myAttendance = $attendanceModel->getByEmployee($employee['id']);
        
        // Fetch my corrections
        $db = new Database();
        $conn = $db->getConnection();
        $cQuery = "SELECT ac.*, a.date FROM attendance_corrections ac LEFT JOIN attendance a ON ac.attendance_id = a.id WHERE ac.employee_id = :emp_id ORDER BY ac.created_at DESC";
        $cStmt = $conn->prepare($cQuery);
        $cStmt->bindParam(':emp_id', $employee['id']);
        $cStmt->execute();
        $myCorrections = $cStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('layouts/employee', [
            'title' => 'My Attendance',
            'content' => 'employee/attendance',
            'myAttendance' => $myAttendance,
            'myCorrections' => $myCorrections
        ]);
    }

    public function leaves() {
        $employeeModel = $this->model('Employee');
        $attendanceModel = $this->model('Attendance');
        $employee = $employeeModel->getByUserId($_SESSION['user_id']);

        $db = new Database();
        $conn = $db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'apply') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            if ($employee['status'] !== 'Active') {
                $_SESSION['leave_error'] = "Inactive employees cannot apply for leave.";
                $this->redirect('/payrollsystem/employee/leaves');
                return;
            }

            $leave_type_id = $_POST['leave_type_id'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $reason = $_POST['reason'];

            $today = date('Y-m-d');
            if ($start_date < $today || $end_date < $today) {
                $_SESSION['leave_error'] = "Leave cannot start in the past.";
                $this->redirect('/payrollsystem/employee/leaves');
                return;
            }
            
            // Check if employee has already clocked in today
            $todayAtt = $attendanceModel->getToday($employee['id']);
            if ($start_date == $today && $todayAtt) {
                $_SESSION['leave_error'] = "You have already checked in today. Your leave request must start from tomorrow.";
                $this->redirect('/payrollsystem/employee/leaves');
                return;
            }

            // Check if there are overtime requests overlapping
            $otQuery = "SELECT id FROM overtime_requests WHERE employee_id = :emp_id AND date >= :sd AND date <= :ed";
            $otStmt = $conn->prepare($otQuery);
            $otStmt->bindParam(':emp_id', $employee['id']);
            $otStmt->bindParam(':sd', $start_date);
            $otStmt->bindParam(':ed', $end_date);
            $otStmt->execute();
            if ($otStmt->rowCount() > 0) {
                $this->redirect('/payrollsystem/employee/leaves');
                return;
            }

            $diff = strtotime($end_date) - strtotime($start_date);
            $days = round($diff / (60 * 60 * 24)) + 1; // inclusive

            // Fetch leave type
            $ltStmt = $conn->prepare("SELECT * FROM leave_types WHERE id = :id");
            $ltStmt->execute([':id' => $leave_type_id]);
            $leaveType = $ltStmt->fetch(PDO::FETCH_ASSOC);

            if (!$leaveType || !$leaveType['is_active']) {
                $_SESSION['leave_error'] = "Invalid or inactive leave policy.";
                $this->redirect('/payrollsystem/employee/leaves');
                return;
            }

            // Gender validation
            if ($leaveType['gender_restriction'] !== 'All') {
                if (($employee['gender'] ?? 'Other') !== $leaveType['gender_restriction']) {
                    $_SESSION['leave_error'] = "This leave is restricted to " . $leaveType['gender_restriction'] . " employees only.";
                    $this->redirect('/payrollsystem/employee/leaves');
                    return;
                }
            }

            // Service period validation
            $joinDate = new DateTime($employee['join_date']);
            $currentDate = new DateTime($today);
            $diffMonths = ($currentDate->format('Y') - $joinDate->format('Y')) * 12 + ($currentDate->format('m') - $joinDate->format('m'));
            if ($diffMonths < $leaveType['service_period_months']) {
                $_SESSION['leave_error'] = "You must complete " . $leaveType['service_period_months'] . " months of service to apply for this leave.";
                $this->redirect('/payrollsystem/employee/leaves');
                return;
            }

            // Quota validation
            if ($leaveType['days_allowed'] < 999) {
                $year = date('Y', strtotime($start_date));
                $sumStmt = $conn->prepare("SELECT SUM(days) as total FROM leave_requests WHERE employee_id = ? AND leave_type_id = ? AND status IN ('Approved', 'Pending') AND YEAR(start_date) = ?");
                $sumStmt->execute([$employee['id'], $leave_type_id, $year]);
                $past_total = $sumStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

                if (($past_total + $days) > $leaveType['days_allowed']) {
                    $_SESSION['leave_error'] = "You do not have enough leave balance for this request. Requested: $days, Available: " . ($leaveType['days_allowed'] - $past_total);
                    $this->redirect('/payrollsystem/employee/leaves');
                    return;
                }
            }

            $query = "INSERT INTO leave_requests SET employee_id=:emp_id, leave_type_id=:lt_id, start_date=:sd, end_date=:ed, days=:days, reason=:reason, status='Pending'";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':emp_id', $employee['id']);
            $stmt->bindParam(':lt_id', $leave_type_id);
            $stmt->bindParam(':sd', $start_date);
            $stmt->bindParam(':ed', $end_date);
            $stmt->bindParam(':days', $days);
            $stmt->bindParam(':reason', $reason);
            $stmt->execute();
            
            $leave_request_id = $conn->lastInsertId();

            // Check if it's Unpaid Leave
            $ltQuery = "SELECT is_paid FROM leave_types WHERE id = :id";
            $ltStmt = $conn->prepare($ltQuery);
            $ltStmt->execute([':id' => $leave_type_id]);
            $is_paid = $ltStmt->fetchColumn();

            if ($is_paid == 0) {
                require_once __DIR__ . '/../models/Deduction.php';
                $deduction = new Deduction();
                $deduction->applyAutomatedRangeDeduction($employee['id'], 'Unpaid Leave', $start_date, $end_date, $days, 'Pending Unpaid Leave Deduction', 'Leave Management System', $leave_request_id, 'Pending');
            }
            
            // Notify Admins
            $adminStmt = $conn->query("SELECT id FROM users WHERE role = 'Admin' AND status = 'Active'");
            $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);
            $notifModel = $this->model('Notification');
            $empName = $employee['first_name'] . ' ' . $employee['last_name'];
            foreach ($admins as $admin) {
                $notifModel->create(
                    $admin['id'],
                    "$empName submitted a Leave Request.",
                    'leave',
                    '/admin/leaves',
                    $empName
                );
            }

            $this->redirect('/payrollsystem/employee/leaves');
        }

        // Fetch my leaves
        $lQuery = "SELECT lr.*, lt.name as leave_type_name, lt.is_paid 
                   FROM leave_requests lr 
                   LEFT JOIN leave_types lt ON lr.leave_type_id = lt.id 
                   WHERE lr.employee_id = :emp_id ORDER BY lr.created_at DESC";
        $lStmt = $conn->prepare($lQuery);
        $lStmt->bindParam(':emp_id', $employee['id']);
        $lStmt->execute();
        $myLeaves = $lStmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch active leave types and calculate balances
        $tQuery = "SELECT * FROM leave_types WHERE is_active = 1 ORDER BY name ASC";
        $tStmt = $conn->prepare($tQuery);
        $tStmt->execute();
        $leaveTypes = $tStmt->fetchAll(PDO::FETCH_ASSOC);

        $leaveBalances = [];
        $currentYear = date('Y');
        
        $joinDate = new DateTime($employee['join_date']);
        $currentDate = new DateTime(date('Y-m-d'));
        $diffMonths = ($currentDate->format('Y') - $joinDate->format('Y')) * 12 + ($currentDate->format('m') - $joinDate->format('m'));
        $employeeGender = $employee['gender'] ?? 'Other';

        foreach ($leaveTypes as $lt) {
            $isEligible = true;
            $reason = "";

            if ($lt['gender_restriction'] !== 'All' && $lt['gender_restriction'] !== $employeeGender) {
                $isEligible = false;
                $reason = "Restricted to {$lt['gender_restriction']} employees.";
            }

            if ($diffMonths < $lt['service_period_months']) {
                $isEligible = false;
                $reason = "Requires {$lt['service_period_months']} months of service.";
            }

            $used = 0;
            if ($lt['days_allowed'] < 999) {
                $sumStmt = $conn->prepare("SELECT SUM(days) as total FROM leave_requests WHERE employee_id = ? AND leave_type_id = ? AND status IN ('Approved', 'Pending') AND YEAR(start_date) = ?");
                $sumStmt->execute([$employee['id'], $lt['id'], $currentYear]);
                $used = $sumStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            }

            $balance = $lt['days_allowed'] >= 999 ? 'Unlimited' : max(0, $lt['days_allowed'] - $used);

            $leaveBalances[] = [
                'id' => $lt['id'],
                'name' => $lt['name'],
                'days_allowed' => $lt['days_allowed'],
                'used' => $used,
                'balance' => $balance,
                'is_eligible' => $isEligible,
                'ineligible_reason' => $reason,
                'is_paid' => $lt['is_paid']
            ];
        }

        $todayAtt = $attendanceModel->getToday($employee['id']);
        $hasClockedInToday = $todayAtt ? true : false;

        $this->view('layouts/employee', [
            'title' => 'Leave Application',
            'content' => 'employee/leaves',
            'myLeaves' => $myLeaves,
            'leaveTypes' => $leaveTypes,
            'leaveBalances' => $leaveBalances,
            'hasClockedInToday' => $hasClockedInToday,
            'user' => $_SESSION
        ]);
    }

    public function overtime() {
        $employeeModel = $this->model('Employee');
        $employee = $employeeModel->getByUserId($_SESSION['user_id']);

        $db = new Database();
        $conn = $db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'apply') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            $date = $_POST['date'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $reason = $_POST['reason'];

            if ($employee['status'] !== 'Active') {
                $_SESSION['ot_error'] = "Your account is inactive.";
                $this->redirect('/payrollsystem/employee/overtime');
                return;
            }

            $today = date('Y-m-d');
            if ($date < $today) {
                $_SESSION['ot_error'] = "Overtime request is only allowed for today or future dates.";
                $this->redirect('/payrollsystem/employee/overtime');
                return;
            }

            // Check if there is a leave request for this date
            $leaveQuery = "SELECT id FROM leave_requests WHERE employee_id = :emp_id AND start_date <= :date AND end_date >= :date AND status = 'Approved'";
            $leaveStmt = $conn->prepare($leaveQuery);
            $leaveStmt->bindParam(':emp_id', $employee['id']);
            $leaveStmt->bindParam(':date', $date);
            $leaveStmt->execute();
            if ($leaveStmt->rowCount() > 0) {
                $_SESSION['ot_error'] = "You cannot request overtime while on approved leave.";
                $this->redirect('/payrollsystem/employee/overtime');
                return;
            }

            // Check if OT already requested for this date
            $otDateQuery = "SELECT id FROM overtime_requests WHERE employee_id = :emp_id AND date = :date";
            $otDateStmt = $conn->prepare($otDateQuery);
            $otDateStmt->bindParam(':emp_id', $employee['id']);
            $otDateStmt->bindParam(':date', $date);
            $otDateStmt->execute();
            if ($otDateStmt->rowCount() > 0) {
                $_SESSION['ot_error'] = "You already have an overtime request for this date.";
                $this->redirect('/payrollsystem/employee/overtime');
                return;
            }

            // Determine Day Type
            $holidayModel = $this->model('Holiday');
            if ($holidayModel->isHoliday($date)) {
                $type = 'Holiday';
            } elseif (date('N', strtotime($date)) >= 6) {
                $type = 'Weekend';
            } else {
                $type = 'Working Day';
            }

            // Time range and duration validation
            $diff = strtotime($end_time) - strtotime($start_time);
            $hours = round($diff / 3600, 2);
            if ($hours <= 0 || $hours > 4) {
                $_SESSION['ot_error'] = "Selected overtime hours are outside the allowed time range.";
                $this->redirect('/payrollsystem/employee/overtime');
                return;
            }

            $st_time = date('H:i', strtotime($start_time));
            $et_time = date('H:i', strtotime($end_time));

            if ($type === 'Working Day') {
                if ($st_time < '17:00' || $et_time > '21:00') {
                    $_SESSION['ot_error'] = "Overtime is not allowed on this date/time range.";
                    $this->redirect('/payrollsystem/employee/overtime');
                    return;
                }
            } else {
                if ($st_time < '09:00' || $et_time > '17:00') {
                    $_SESSION['ot_error'] = "Overtime is not allowed on this date/time range.";
                    $this->redirect('/payrollsystem/employee/overtime');
                    return;
                }
            }

            // Monthly limit check
            $month = date('m', strtotime($date));
            $year = date('Y', strtotime($date));
            $limitQuery = "SELECT SUM(hours) as total_hours FROM overtime_requests WHERE employee_id = :emp_id AND MONTH(date) = :m AND YEAR(date) = :y AND status != 'Rejected'";
            $limitStmt = $conn->prepare($limitQuery);
            $limitStmt->bindParam(':emp_id', $employee['id']);
            $limitStmt->bindParam(':m', $month);
            $limitStmt->bindParam(':y', $year);
            $limitStmt->execute();
            $row = $limitStmt->fetch(PDO::FETCH_ASSOC);
            $total_hours = $row['total_hours'] ? $row['total_hours'] : 0;
            
            // Get setting limit
            $settingStmt = $conn->query("SELECT max_ot_hours FROM settings LIMIT 1");
            $max_ot_hours = $settingStmt->fetch(PDO::FETCH_ASSOC)['max_ot_hours'] ?? 60;
            
            if ($total_hours + $hours > $max_ot_hours) {
                $_SESSION['ot_error'] = "Monthly overtime limit has been exceeded.";
                $this->redirect('/payrollsystem/employee/overtime');
                return;
            }

            $query = "INSERT INTO overtime_requests SET employee_id=:emp_id, date=:date, start_time=:st, end_time=:et, hours=:hours, type=:type, reason=:reason, status='Pending'";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':emp_id', $employee['id']);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':st', $start_time);
            $stmt->bindParam(':et', $end_time);
            $stmt->bindParam(':hours', $hours);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':reason', $reason);
            $stmt->execute();
            
            // Notify Admins
            $adminStmt = $conn->query("SELECT id FROM users WHERE role = 'Admin' AND status = 'Active'");
            $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);
            $notifModel = $this->model('Notification');
            $empName = $employee['first_name'] . ' ' . $employee['last_name'];
            foreach ($admins as $admin) {
                $notifModel->create(
                    $admin['id'],
                    "$empName submitted an Overtime Request.",
                    'overtime',
                    '/admin/overtime',
                    $empName
                );
            }

            $this->redirect('/payrollsystem/employee/overtime');
        }

        // Fetch my overtime requests
        $oQuery = "SELECT * FROM overtime_requests WHERE employee_id = :emp_id ORDER BY created_at DESC";
        $oStmt = $conn->prepare($oQuery);
        $oStmt->bindParam(':emp_id', $employee['id']);
        $oStmt->execute();
        $myOvertime = $oStmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch admin assigned overtimes
        $oaQuery = "SELECT oa.* FROM overtime_assignments oa
                    JOIN overtime_assignment_employees oae ON oa.id = oae.assignment_id
                    WHERE oae.employee_id = :emp_id ORDER BY oa.date DESC";
        $oaStmt = $conn->prepare($oaQuery);
        $oaStmt->bindParam(':emp_id', $employee['id']);
        $oaStmt->execute();
        $myAssignments = $oaStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('layouts/employee', [
            'title' => 'Overtime Application',
            'content' => 'employee/overtime',
            'myOvertime' => $myOvertime,
            'myAssignments' => $myAssignments
        ]);
    }

    public function payslips() {
        $employeeModel = $this->model('Employee');
        $payrollModel = $this->model('Payroll');
        
        $employee = $employeeModel->getByUserId($_SESSION['user_id']);
        $myPayslips = $payrollModel->getByEmployee($employee['id']);

        $this->view('layouts/employee', [
            'title' => 'My Payslips',
            'content' => 'employee/payslips',
            'myPayslips' => $myPayslips
        ]);
    }
}
