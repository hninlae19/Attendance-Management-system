<?php
/**
 * CRON JOB: Overtime Auto-Checkout and Missed Check-in
 * 
 * This script should be run periodically (e.g., every 5 minutes) via a cron job
 * or Windows Task Scheduler to automate overtime states.
 */

date_default_timezone_set('Asia/Yangon');
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/OvertimeAssign.php';

try {
    $overtimeModel = new OvertimeAssign();
    $overtimeModel->autoUpdateStatuses();

    // Log the output
    echo "[" . date('Y-m-d H:i:s') . "] CRON Overtime Executed successfully: unaccepted assignments (>30 mins before start) marked NoOT, missed check-ins (>30 mins after start) marked NoOT, and finished shifts marked Completed.\n";

} catch (PDOException $e) {
    echo "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n";
}
?>
