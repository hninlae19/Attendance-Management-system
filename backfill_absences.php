<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Holiday.php';
require_once __DIR__ . '/models/Deduction.php';
require_once __DIR__ . '/models/Setting.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $holidayModel = new Holiday();
    $deductionModel = new Deduction();
    
    // Start from the beginning of the current month, up to today
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-d');
    
    $current_date = $start_date;
    $total_marked = 0;
    
    while (strtotime($current_date) <= strtotime($end_date)) {
        // Skip holidays and weekends
        if (!$holidayModel->isHoliday($current_date) && date('N', strtotime($current_date)) < 6) {
            
            // If today, only process if time is past office end time
            if ($current_date === date('Y-m-d')) {
                $settingModel = new Setting();
                $settings = $settingModel->getSettings();
                $office_end = $settings['office_end_time'] ?? '17:00:00';
                if (date('H:i:s') < $office_end) {
                    $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
                    continue; // Skip today because office hasn't ended
                }
            }
            
            $empQuery = "SELECT e.id, e.basic_salary FROM employees e 
                         JOIN users u ON e.user_id = u.id
                         WHERE u.status = 'Active' 
                         AND e.id NOT IN (SELECT employee_id FROM attendance WHERE date = :date) 
                         AND e.id NOT IN (SELECT employee_id FROM leave_requests WHERE :date BETWEEN start_date AND end_date AND status = 'Approved')";
            $empStmt = $conn->prepare($empQuery);
            $empStmt->bindParam(':date', $current_date);
            $empStmt->execute();
            $absent_employees = $empStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($absent_employees as $emp) {
                // Mark Absent
                $ins = "INSERT INTO attendance (employee_id, date, status) VALUES (:emp_id, :date, 'Absent')";
                $insStmt = $conn->prepare($ins);
                $insStmt->bindParam(':emp_id', $emp['id']);
                $insStmt->bindParam(':date', $current_date);
                $insStmt->execute();

                $deductionModel->applyAutomatedDeduction($emp['id'], 'Full Day Absence', $current_date, 'Unauthorized Absence on ' . $current_date, 'Attendance System');
                $total_marked++;
            }
        }
        $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
    }
    
    echo "Successfully marked $total_marked absent records for missed check-ins.";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
