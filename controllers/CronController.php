<?php
class CronController extends Controller {
    public function run() {
        // This endpoint should ideally be protected or called via CLI.
        // For demonstration, we allow HTTP GET.
        
        $settingModel = $this->model('Setting');
        $attendanceModel = $this->model('Attendance');
        $settings = $settingModel->getSettings();
        
        $auto_checkout_time = $settings['auto_checkout_time'] ?? '17:30:00';
        $current_time = date('H:i:s');
        $date = date('Y-m-d');
        
        $db = new Database();
        $conn = $db->getConnection();

        $output = [];

        // 1. Auto Check-Out
        if (strtotime($current_time) >= strtotime($auto_checkout_time)) {
            // Find all attendances for today without a check_out
            $query = "SELECT * FROM attendance WHERE date = :date AND check_out IS NULL";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            $missed_checkouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($missed_checkouts as $att) {
                // Auto check out at office end time or auto checkout time
                $attendanceModel->clockOut($att['id'], $att['check_in']);
                
                // Update to set check_out time specifically to office_end_time for penalty
                $office_end = $settings['office_end_time'] ?? '17:00:00';
                $upd = "UPDATE attendance SET check_out = :time WHERE id = :id";
                $updStmt = $conn->prepare($upd);
                $updStmt->bindParam(':time', $office_end);
                $updStmt->bindParam(':id', $att['id']);
                $updStmt->execute();
            }
            $output[] = "Auto check-out processed for " . count($missed_checkouts) . " employees.";
        } else {
            $output[] = "Too early for auto check-out.";
        }

        echo implode("<br>", $output);
    }
}
