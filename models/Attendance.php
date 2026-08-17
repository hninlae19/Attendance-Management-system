<?php
require_once __DIR__ . '/../config/database.php';

class Attendance {
    private $conn;
    private $table = 'Attendance';

    public $AttendanceID;
    public $EmpID;
    public $CheckInTime;
    public $CheckOutTime;
    public $AttendanceDate;
    public $Status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function checkIn($emp_id, $time, $date) {
        $status = ($time > '09:15:00') ? 'Late' : 'Present';
        $query = "INSERT INTO " . $this->table . " 
                  SET EmpID = :emp_id, CheckInTime = :time, AttendanceDate = :date, Status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function checkOut($emp_id, $time, $date) {
        // Fetch check in time
        $record = $this->getTodayRecord($emp_id, $date);
        if (!$record) return false;

        $status = $this->calculateStatus($record['CheckInTime'], $time);

        $query = "UPDATE " . $this->table . " 
                  SET CheckOutTime = :time, Status = :status
                  WHERE EmpID = :emp_id AND AttendanceDate = :date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':date', $date);
        return $stmt->execute();
    }

    public function calculateStatus($checkInTime, $checkOutTime) {
        if (empty($checkInTime) || empty($checkOutTime)) {
            return 'Full-Day Absence';
        }

        $in = strtotime($checkInTime);
        $out = strtotime($checkOutTime);
        $workingHours = round(abs($out - $in) / 3600, 2);

        $lateThreshold = strtotime('09:15:00');

        if ($workingHours < 4) {
            return 'Full-Day Absence';
        } elseif ($workingHours >= 4 && $workingHours < 6) {
            return 'Half-Day Absence';
        } elseif ($workingHours >= 6 && $workingHours < 7.75) {
            return 'Late';
        } else {
            // $workingHours >= 7.75
            if (date('H:i:s', $in) > '09:15:00') {
                return 'Late';
            } else {
                return 'Present';
            }
        }
    }

    public function processAutoCheckouts() {
        // First ensure the column exists (safe to run once)
        try {
            $this->conn->exec("ALTER TABLE " . $this->table . " ADD COLUMN is_auto_checkout TINYINT(1) DEFAULT 0");
        } catch (PDOException $e) {
            // Column already exists, ignore
        }

        // Find records that need auto check-out
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE CheckOutTime IS NULL 
                  AND (AttendanceDate < CURRENT_DATE() 
                       OR (AttendanceDate = CURRENT_DATE() AND CURRENT_TIME() >= '17:15:00'))";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $updateQuery = "UPDATE " . $this->table . " 
                        SET CheckOutTime = '17:15:00', Status = :status, is_auto_checkout = 1
                        WHERE AttendanceID = :id";
        $updateStmt = $this->conn->prepare($updateQuery);

        foreach ($records as $record) {
            $status = $this->calculateStatus($record['CheckInTime'], '17:15:00');
            $updateStmt->bindParam(':status', $status);
            $updateStmt->bindParam(':id', $record['AttendanceID']);
            $updateStmt->execute();
        }
    }

    public function processFullDayAbsences() {
        $datesToCheck = [];
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');
        
        // Always check yesterday to ensure we caught any missed absences
        $datesToCheck[] = date('Y-m-d', strtotime('-1 day'));
        
        // If it's past end-of-day, check today as well
        if ($currentTime >= '17:15:00') {
            $datesToCheck[] = $today;
        }


        // Get all active employees
        $empQuery = "SELECT EmpID, JoinDate FROM Employee WHERE Status = 'Active'";
        $empStmt = $this->conn->prepare($empQuery);
        $empStmt->execute();
        $activeEmployees = $empStmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($activeEmployees)) return;

        foreach ($datesToCheck as $date) {
            // Skip non-working days (Weekends and Public Holidays)
            if (!HolidayHelper::isWorkingDay($date)) {
                continue;
            }



            // Check each employee
            $insertQuery = "INSERT INTO " . $this->table . " (EmpID, AttendanceDate, Status) VALUES (:emp_id, :date, 'Full-Day Absence')";
            $insertStmt = $this->conn->prepare($insertQuery);

            foreach ($activeEmployees as $emp) {
                // Skip if employee joined after this date
                if (!empty($emp['JoinDate']) && $date < $emp['JoinDate']) {
                    continue;
                }

                // Check if they have an attendance record
                $chkAtt = $this->conn->prepare("SELECT AttendanceID FROM " . $this->table . " WHERE EmpID = ? AND AttendanceDate = ?");
                $chkAtt->execute([$emp['EmpID'], $date]);
                if ($chkAtt->rowCount() > 0) continue;

                // Check if they have an approved leave request
                $chkLeave = $this->conn->prepare("SELECT RequestID FROM LeaveRequest WHERE EmpID = ? AND ? BETWEEN StartDate AND EndDate AND Status = 'Approved'");
                $chkLeave->execute([$emp['EmpID'], $date]);
                if ($chkLeave->rowCount() > 0) continue;

                // If no attendance and no leave, insert Full-Day Absence
                try {
                    $insertStmt->bindParam(':emp_id', $emp['EmpID']);
                    $insertStmt->bindParam(':date', $date);
                    $insertStmt->execute();
                } catch (PDOException $e) {
                    // Ignore duplicate key errors if any
                }
            }
        }
    }

    public function getTodayRecord($emp_id, $date) {
        $query = "SELECT * FROM " . $this->table . " WHERE EmpID = :emp AND AttendanceDate = :date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp', $emp_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getEmployeeRecords($emp_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE EmpID = :emp ORDER BY AttendanceDate DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':emp', $emp_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRecords() {
        $query = "SELECT a.*, e.FirstName, e.LastName FROM " . $this->table . " a
                  LEFT JOIN Employee e ON a.EmpID = e.EmpID
                  ORDER BY a.AttendanceDate DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
