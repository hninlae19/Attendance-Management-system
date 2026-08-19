<?php
/**
 * CRON JOB: Overtime Auto-Checkout and Missed Check-in
 * 
 * This script should be run periodically (e.g., every 5 minutes) via a cron job
 * or Windows Task Scheduler to automate overtime states.
 */

require_once __DIR__ . '/config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    // 1. Auto-Checkout (Completion)
    // If current time reaches or exceeds EndTime and Status is InProgress, update to Completed.
    $autoCheckoutQuery = "UPDATE overtimeassign 
                          SET Status = 'Completed' 
                          WHERE Status = 'InProgress' AND NOW() >= EndTime";
    $stmt1 = $conn->prepare($autoCheckoutQuery);
    $stmt1->execute();
    $completedCount = $stmt1->rowCount();

    // 2. Missed Check-in (NoOT)
    // If current time has passed StartTime by 30 minutes and Status is still Accepted.
    $missedCheckinQuery = "UPDATE overtimeassign 
                           SET Status = 'NoOT' 
                           WHERE Status = 'Accepted' AND NOW() > (StartTime + INTERVAL 30 MINUTE)";
    $stmt2 = $conn->prepare($missedCheckinQuery);
    $stmt2->execute();
    $missedCount = $stmt2->rowCount();

    // Log the output
    echo "[" . date('Y-m-d H:i:s') . "] CRON Overtime Executed.\n";
    echo " -> Auto-Completed: $completedCount records.\n";
    echo " -> Missed (NoOT): $missedCount records.\n";

} catch (PDOException $e) {
    echo "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n";
}
?>
