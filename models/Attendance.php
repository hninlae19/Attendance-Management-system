<?php
require_once __DIR__ . '/Setting.php';

class Attendance {
    private $conn;
    private $table = 'attendance';

    public $id;
    public $employee_id;
    public $date;
    public $check_in;
    public $check_out;
    public $working_hours;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($date = null) {
        $query = "SELECT a.*, e.first_name, e.last_name, e.employee_code, d.name as department_name 
                  FROM " . $this->table . " a
                  LEFT JOIN employees e ON a.employee_id = e.id
                  LEFT JOIN departments d ON e.department_id = d.id";
        
        if ($date) {
            $query .= " WHERE a.date = :date";
        }
        
        $query .= " ORDER BY a.date DESC, e.first_name ASC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($date) {
            $stmt->bindParam(':date', $date);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByEmployee($employee_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE employee_id = :employee_id ORDER BY date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getToday($employee_id) {
        $date = date('Y-m-d');
        $query = "SELECT * FROM " . $this->table . " WHERE employee_id = :employee_id AND date = :date LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function clockIn($employee_id) {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
        
        $status = 'Present';
        if (strtotime($time) > strtotime($settings['late_time'])) {
            $status = 'Late';
        }

        $query = "INSERT INTO " . $this->table . " SET employee_id=:emp_id, date=:date, check_in=:time, status=:status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":emp_id", $employee_id);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":time", $time);
        $stmt->bindParam(":status", $status);
        
        if ($stmt->execute()) {
            $last_id = $this->conn->lastInsertId();
            if ($status === 'Late') {
                require_once __DIR__ . '/Deduction.php';
                $deduction = new Deduction();
                
                $monthStart = date('Y-m-01', strtotime($date));
                $monthEnd = date('Y-m-t', strtotime($date));
                
                $lateCountStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE employee_id = ? AND status = 'Late' AND date BETWEEN ? AND ?");
                $lateCountStmt->execute([$employee_id, $monthStart, $monthEnd]);
                $lateCount = $lateCountStmt->fetchColumn();
                
                if ($lateCount > 0 && $lateCount % 3 == 0) {
                    $deduction->applyAutomatedDeduction($employee_id, 'Half Day Absence', $date, 'Penalty for 3 Lates in month', 'Attendance System', $last_id, 0.5);
                }
            }
            return true;
        }
        return false;
    }

            public function autoCheckoutIfMissed($employee_id = null) {
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
        $auto_checkout_time = $settings['auto_checkout_time'] ?? '17:30:00';
        
        $query = "SELECT * FROM " . $this->table . " WHERE check_out IS NULL";
        if ($employee_id) {
            $query .= " AND employee_id = :emp_id";
        }
        $stmt = $this->conn->prepare($query);
        if ($employee_id) {
            $stmt->bindParam(':emp_id', $employee_id);
        }
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($records as $rec) {
            $expected_checkout = $auto_checkout_time;
            
            // Check for approved overtime requests
            $ot_query = "SELECT end_time FROM overtime_requests WHERE employee_id = :emp AND date = :date AND status = 'Approved'";
            $ot_stmt = $this->conn->prepare($ot_query);
            $ot_stmt->execute([':emp' => $rec['employee_id'], ':date' => $rec['date']]);
            $ot_req = $ot_stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check for active overtime assignments
            $ota_query = "SELECT a.end_time FROM overtime_assignments a JOIN overtime_assignment_employees e ON a.id = e.assignment_id WHERE e.employee_id = :emp AND a.date = :date AND e.status != 'Missed'";
            $ota_stmt = $this->conn->prepare($ota_query);
            $ota_stmt->execute([':emp' => $rec['employee_id'], ':date' => $rec['date']]);
            $ota_req = $ota_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($ot_req) {
                $expected_checkout = date('H:i:s', strtotime($ot_req['end_time']) + 15 * 60);
            } elseif ($ota_req) {
                $expected_checkout = date('H:i:s', strtotime($ota_req['end_time']) + 15 * 60);
            }
            
            $should_auto_checkout = false;
            if ($rec['date'] < $current_date) {
                $should_auto_checkout = true;
            } elseif ($rec['date'] == $current_date && $current_time >= $expected_checkout) {
                $should_auto_checkout = true;
            }
            
            if ($should_auto_checkout) {
                $this->clockOut($rec['id'], $rec['check_in'], $expected_checkout, 1);
            }
        }
    }

    public function clockOut($id, $check_in_time, $checkout_time_override = null, $is_auto = 0) {
        $time = $checkout_time_override ? $checkout_time_override : date('H:i:s');
        
        $t1 = strtotime($check_in_time);
        $t2 = strtotime($time);
        $hours = round(($t2 - $t1) / 3600, 2);

        $status = 'Present';
        if ($hours >= 8) {
            $status = 'Present'; // Full Pay
        } elseif ($hours >= 4 && $hours < 8) {
            $status = 'Half Day'; // 0.5 Day Pay
        } else {
            $status = 'Absent'; // No Pay
        }

        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
        $office_end = $settings['office_end_time'] ?? '17:00:00';

        // Half-Day Rules overrides
        // Check-in between 12 PM and office_end
        if (strtotime($check_in_time) >= strtotime('12:00:00') && strtotime($check_in_time) <= strtotime($office_end)) {
            $status = 'Half Day';
        }
        // Check-out between 12 PM and office_end
        if (strtotime($time) >= strtotime('12:00:00') && strtotime($time) < strtotime($office_end) && $hours < 7) {
            $status = 'Half Day';
        }
        // if total hours < 4, it is always Absent regardless of time overrides.
        if ($hours < 4) {
            $status = 'Absent';
        }

        $query = "UPDATE " . $this->table . " SET check_out=:time, working_hours=:hours, status=:status, is_auto_checkout=:is_auto WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":time", $time);
        $stmt->bindParam(":hours", $hours);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":is_auto", $is_auto);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) {
            require_once __DIR__ . '/Deduction.php';
            $deduction = new Deduction();
            
            $empStmt = $this->conn->prepare("SELECT employee_id, date FROM " . $this->table . " WHERE id = ?");
            $empStmt->execute([$id]);
            $attRecord = $empStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($attRecord) {
                if ($status === 'Absent') {
                    $deduction->applyAutomatedDeduction($attRecord['employee_id'], 'Full Day Absence', $attRecord['date'], 'Automated Full Day Absence Deduction (Early Check-Out)', 'Attendance System', $id, 1.0);
                } elseif ($status === 'Half Day') {
                    $deduction->applyAutomatedDeduction($attRecord['employee_id'], 'Half Day Absence', $attRecord['date'], 'Automated Half Day Absence Deduction (Early Check-Out)', 'Attendance System', $id, 0.5);
                }
            }

            return true;
        }
        return false;
    }

    public function getCorrections() {
        $query = "SELECT ac.*, a.date, a.check_in as old_in, a.check_out as old_out, 
                         e.first_name, e.last_name, e.employee_code 
                  FROM attendance_corrections ac
                  LEFT JOIN attendance a ON ac.attendance_id = a.id
                  LEFT JOIN employees e ON ac.employee_id = e.id
                  ORDER BY ac.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handleCorrection($id, $action) {
        // Fetch correction request
        $query = "SELECT * FROM attendance_corrections WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $correction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$correction) return false;

        try {
            $this->conn->beginTransaction();

            $status = ($action === 'approve') ? 'Approved' : 'Rejected';
            
            // Update correction status
            $updQuery = "UPDATE attendance_corrections SET status = :status WHERE id = :id";
            $updStmt = $this->conn->prepare($updQuery);
            $updStmt->bindParam(':status', $status);
            $updStmt->bindParam(':id', $id);
            $updStmt->execute();

            if ($action === 'approve') {
                $check_in = $correction['corrected_check_in'];
                $check_out = $correction['corrected_check_out'];
                
                $hours = 0;
                $newStatus = 'Absent';
                
                if ($check_in && $check_out) {
                    $t1 = strtotime($check_in);
                    $t2 = strtotime($check_out);
                    $hours = round(($t2 - $t1) / 3600, 2);
                    
                    if ($hours >= 8) {
                        $newStatus = 'Present';
                    } elseif ($hours >= 4 && $hours < 8) {
                        $newStatus = 'Half Day';
                    } else {
                        $newStatus = 'Absent';
                    }

                    if (strtotime($check_in) >= strtotime('12:00:00') && strtotime($check_in) <= strtotime('17:00:00')) {
                        $newStatus = 'Half Day';
                    }
                    if (strtotime($check_out) >= strtotime('12:00:00') && strtotime($check_out) < strtotime('17:00:00') && $hours < 7) {
                        $newStatus = 'Half Day';
                    }
                    if ($hours < 4) {
                        $newStatus = 'Absent';
                    }
                } elseif ($check_in) {
                    $settingModel = new Setting();
                    $settings = $settingModel->getSettings();
                    $newStatus = 'Present';
                    if (strtotime($check_in) > strtotime($settings['late_time'] ?? '09:00:00')) {
                        $newStatus = 'Late';
                    }
                }

                // Update actual attendance record
                $attQuery = "UPDATE attendance SET check_in = :check_in, check_out = :check_out, working_hours = :hours, status = :status WHERE id = :att_id";
                $attStmt = $this->conn->prepare($attQuery);
                $attStmt->bindParam(':check_in', $check_in);
                $attStmt->bindParam(':check_out', $check_out);
                $attStmt->bindParam(':hours', $hours);
                $attStmt->bindParam(':status', $newStatus);
                $attStmt->bindParam(':att_id', $correction['attendance_id']);
                $attStmt->execute();
                
                // Handle Deductions
                require_once __DIR__ . '/Deduction.php';
                $deduction = new Deduction();
                $attStmt2 = $this->conn->prepare("SELECT date FROM attendance WHERE id = ?");
                $attStmt2->execute([$correction['attendance_id']]);
                $att_date = $attStmt2->fetchColumn();
                
                $deduction->cancelAutomatedDeduction($correction['employee_id'], 'Full Day Absence', $att_date, $correction['attendance_id']);
                $deduction->cancelAutomatedDeduction($correction['employee_id'], 'Half Day Absence', $att_date, $correction['attendance_id']);
                $deduction->cancelAutomatedDeduction($correction['employee_id'], 'Late', $att_date, $correction['attendance_id']);
                
                if ($newStatus === 'Absent') {
                    $deduction->applyAutomatedDeduction($correction['employee_id'], 'Full Day Absence', $att_date, 'Automated Full Day Absence Deduction', 'Attendance System', $correction['attendance_id'], 1.0);
                } elseif ($newStatus === 'Half Day') {
                    $deduction->applyAutomatedDeduction($correction['employee_id'], 'Half Day Absence', $att_date, 'Automated Half Day Absence Deduction', 'Attendance System', $correction['attendance_id'], 0.5);
                } elseif ($newStatus === 'Late') {
                    $monthStart = date('Y-m-01', strtotime($att_date));
                    $monthEnd = date('Y-m-t', strtotime($att_date));
                    
                    $lateCountStmt = $this->conn->prepare("SELECT COUNT(*) FROM attendance WHERE employee_id = ? AND status = 'Late' AND date BETWEEN ? AND ?");
                    $lateCountStmt->execute([$correction['employee_id'], $monthStart, $monthEnd]);
                    $lateCount = $lateCountStmt->fetchColumn();
                    
                    if ($lateCount > 0 && $lateCount % 3 == 0) {
                        $deduction->applyAutomatedDeduction($correction['employee_id'], 'Half Day Absence', $att_date, 'Penalty for 3 Lates in month', 'Attendance System', $correction['attendance_id'], 0.5);
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getTotalAttendanceCount($filters = []) {
        $query = "SELECT COUNT(a.id) as total
                  FROM " . $this->table . " a
                  LEFT JOIN employees e ON a.employee_id = e.id
                  LEFT JOIN departments d ON e.department_id = d.id
                  WHERE 1=1";
        
        $params = $this->buildFilterParams($query, $filters);
        
        $stmt = $this->conn->prepare($query);
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getPaginatedAttendance($filters = [], $limit = 10, $offset = 0) {
        $query = "SELECT a.*, e.first_name, e.last_name, e.employee_code, e.basic_salary, 
                         d.name as department_name, p.name as position_name,
                         ot.hours as ot_hours, ot.start_time as ot_start, ot.end_time as ot_end, ot.type as ot_type, ot.status as ot_status, ot.reason as ot_remark
                  FROM " . $this->table . " a
                  LEFT JOIN employees e ON a.employee_id = e.id
                  LEFT JOIN departments d ON e.department_id = d.id
                  LEFT JOIN positions p ON e.position_id = p.id
                  LEFT JOIN overtime_requests ot ON a.employee_id = ot.employee_id AND a.date = ot.date AND ot.status = 'Approved'
                  WHERE 1=1";
        
        $params = $this->buildFilterParams($query, $filters);
        
        $query .= " ORDER BY a.date DESC, e.first_name ASC";
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settingModel = new Setting();
        $settings = $settingModel->getSettings();

        foreach ($results as &$row) {
            $row['ot_amount'] = 0;
            $row['ot_rate'] = 0;
            $row['late_minutes'] = 0;

            // Calculate Late Minutes
            if ($row['status'] === 'Late' && $row['check_in']) {
                $checkInTime = strtotime($row['check_in']);
                $lateTimeLimit = strtotime($settings['late_time'] ?? '09:00:00');
                if ($checkInTime > $lateTimeLimit) {
                    $row['late_minutes'] = round(($checkInTime - $lateTimeLimit) / 60);
                }
            }

            // Calculate OT Amount & Rate
            if (!empty($row['ot_hours'])) {
                $dailyRate = ($row['basic_salary'] ?? 0) / 30;
                $hourlyRate = $dailyRate / ($settings['working_hours'] ?? 8);
                $rateMultiplier = 0;
                
                if ($row['ot_type'] === 'Working Day') $rateMultiplier = $settings['weekday_ot_rate'] ?? 1.5;
                if ($row['ot_type'] === 'Weekend') $rateMultiplier = $settings['weekend_ot_rate'] ?? 2.0;
                if ($row['ot_type'] === 'Holiday') $rateMultiplier = $settings['holiday_ot_rate'] ?? 3.0;
                
                $row['ot_rate'] = $hourlyRate * $rateMultiplier;
                $row['ot_amount'] = $row['ot_rate'] * $row['ot_hours'];
            }
        }
        return $results;
    }

    private function buildFilterParams(&$query, $filters) {
        $params = [];
        
        if (!empty($filters['date_start']) && !empty($filters['date_end'])) {
            $query .= " AND a.date BETWEEN :date_start AND :date_end";
            $params[':date_start'] = $filters['date_start'];
            $params[':date_end'] = $filters['date_end'];
        } elseif (!empty($filters['date_start'])) {
            $query .= " AND a.date = :date_start";
            $params[':date_start'] = $filters['date_start'];
        }

        if (!empty($filters['department_id'])) {
            $query .= " AND e.department_id = :dept_id";
            $params[':dept_id'] = $filters['department_id'];
        }

        if (!empty($filters['employee_id'])) {
            $query .= " AND a.employee_id = :emp_id";
            $params[':emp_id'] = $filters['employee_id'];
        }

        if (!empty($filters['status'])) {
            $query .= " AND a.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (e.first_name LIKE :search OR e.last_name LIKE :search OR e.employee_code LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        return $params;
    }
}
