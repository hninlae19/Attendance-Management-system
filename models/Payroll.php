<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/HolidayHelper.php';
require_once __DIR__ . '/Attendance.php';

class Payroll {
    private $conn;
    private $table = 'Payroll';

    public $PayrollID;
    public $EmpID;
    public $BasicSalary;
    public $PayrollMonth;
    public $PayableDays;
    public $BonousAmount;
    public $OvertimeAmount;
    public $LeaveDeductionAmount;
    public $NetSalary;
    public $Status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT p.*, e.FirstName, e.LastName, e.JoinDate, pos.PositionName, d.DeptName 
                  FROM " . $this->table . " p
                  LEFT JOIN Employee e ON p.EmpID = e.EmpID
                  LEFT JOIN Position pos ON e.PositionID = pos.PositionID
                  LEFT JOIN Department d ON pos.DeptID = d.DeptID
                  ORDER BY p.EmpID ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $payrolls = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->appendDynamicFields($payrolls);
    }

    public function getByEmployee($emp_id) {
        $query = "SELECT p.*, e.FirstName, e.LastName, e.JoinDate, pos.PositionName, d.DeptName 
                  FROM " . $this->table . " p
                  LEFT JOIN Employee e ON p.EmpID = e.EmpID
                  LEFT JOIN Position pos ON e.PositionID = pos.PositionID
                  LEFT JOIN Department d ON pos.DeptID = d.DeptID
                  WHERE p.EmpID = :emp_id 
                  ORDER BY p.PayrollID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        $payrolls = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->appendDynamicFields($payrolls);
    }

    public function getById($id) {
        $query = "SELECT p.*, e.FirstName, e.LastName, e.JoinDate, pos.PositionName, d.DeptName 
                  FROM " . $this->table . " p
                  LEFT JOIN Employee e ON p.EmpID = e.EmpID
                  LEFT JOIN Position pos ON e.PositionID = pos.PositionID
                  LEFT JOIN Department d ON pos.DeptID = d.DeptID
                  WHERE p.PayrollID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $payroll = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($payroll) {
            $payrolls = $this->appendDynamicFields([$payroll]);
            return $payrolls[0];
        }
        return false;
    }
    
    private function appendDynamicFields($payrolls) {
        if (empty($payrolls)) return $payrolls;
        
        foreach ($payrolls as &$p) {
            $monthStr = $p['PayrollMonth'];
            $year = date('Y');
            $month = date('m');
            // Parse monthStr which could be "July 2026" or "2026-07"
            if (preg_match('/^(\d{4})-(\d{2})$/', $monthStr, $matches)) {
                $year = $matches[1];
                $month = $matches[2];
            } else {
                $time = strtotime("1 " . $monthStr);
                if ($time) {
                    $year = date('Y', $time);
                    $month = date('m', $time);
                }
            }
            $startDate = "$year-$month-01";
            $endDate = date("Y-m-t", strtotime($startDate));
            $empId = $p['EmpID'];
            
            // Working days & salary rates
            $workingDaysCount = HolidayHelper::getWorkingDaysCountInMonth($year, $month, $p['JoinDate'] ?? null);
            $basicSalary = (float)($p['BasicSalary'] ?? 0);
            $dailySalary = $workingDaysCount > 0 ? ($basicSalary / $workingDaysCount) : 0;
            $hourlyRate = $dailySalary / 8;
            
            $p['working_days_count'] = $workingDaysCount;
            $p['daily_salary'] = round($dailySalary, 2);
            $p['hourly_rate'] = round($hourlyRate, 2);
            
            // Attendance stats & Late minutes
            $stmt = $this->conn->prepare("
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
                
                // Calculate dynamic late minutes from CheckInTime relative to 09:00 AM
                if (!empty($att['CheckInTime'])) {
                    $totalLateMinutes += Attendance::calculateLateMinutes($att['CheckInTime']);
                }
            }
            
            $p['present_days'] = $presentDays;
            $p['absent_days'] = $absentDays;
            $p['half_days'] = $halfDays;
            $p['late_days'] = $lateDays;
            $p['late_minutes'] = $totalLateMinutes;
            
            $lateHours = $totalLateMinutes / 60;
            $p['late_hours'] = round($lateHours, 2);
            
            // Deductions
            $lateDeduction = round($hourlyRate * $lateHours, 2);
            $halfDayDeduction = round($halfDays * ($dailySalary * 0.5), 2);
            $fullDayDeduction = round($absentDays * $dailySalary, 2);
            $totalAttendanceDeduction = $lateDeduction + $halfDayDeduction + $fullDayDeduction;
            
            $p['late_deduction'] = $lateDeduction;
            $p['half_day_deduction'] = $halfDayDeduction;
            $p['full_day_deduction'] = $fullDayDeduction;
            $p['total_attendance_deduction'] = $totalAttendanceDeduction;
            
            // OT stats
            $stmt = $this->conn->prepare("SELECT SUM(TotalHours) as ot_hours FROM overtimeassign WHERE EmpID = :emp AND OvertimeDate BETWEEN :sd AND :ed AND Status = 'Completed'");
            $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
            $p['ot_hours'] = $stmt->fetchColumn() ?: 0;
            
            // Leave stats
            $stmt = $this->conn->prepare("
                SELECT StartDate, EndDate 
                FROM leaverequest 
                WHERE EmpID = :emp AND Status = 'Approved'
                AND StartDate <= :ed AND EndDate >= :sd
            ");
            $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
            $leavesThisMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $leaveDaysInMonth = 0;
            foreach ($leavesThisMonth as $lr) {
                $lrStart = max(strtotime($startDate), strtotime($lr['StartDate']));
                $lrEnd = min(strtotime($endDate), strtotime($lr['EndDate']));
                $days = round(($lrEnd - $lrStart) / (60 * 60 * 24)) + 1;
                if ($days > 0) $leaveDaysInMonth += $days;
            }
            $p['leave_days'] = $leaveDaysInMonth;
        }
        return $payrolls;
    }
}

