<?php
require_once __DIR__ . '/../config/database.php';

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
        $query = "SELECT p.*, e.FirstName, e.LastName, pos.PositionName, d.DeptName 
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
        $query = "SELECT p.*, e.FirstName, e.LastName, pos.PositionName, d.DeptName 
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
        $query = "SELECT p.*, e.FirstName, e.LastName, pos.PositionName, d.DeptName 
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
            
            // Attendance stats
            $stmt = $this->conn->prepare("
                SELECT 
                    SUM(CASE WHEN CheckInTime IS NOT NULL THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN Status = 'Full-Day Absence' THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN Status = 'Half-Day Absence' THEN 1 ELSE 0 END) as half_days,
                    SUM(CASE WHEN Status = 'Late' THEN 1 ELSE 0 END) as late_days
                FROM attendance 
                WHERE EmpID = :emp AND AttendanceDate BETWEEN :sd AND :ed
            ");
            $stmt->execute([':emp' => $empId, ':sd' => $startDate, ':ed' => $endDate]);
            $attStats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $p['present_days'] = $attStats['present_days'] ?: 0;
            $p['absent_days'] = $attStats['absent_days'] ?: 0;
            $p['half_days'] = $attStats['half_days'] ?: 0;
            $p['late_days'] = $attStats['late_days'] ?: 0;
            
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
