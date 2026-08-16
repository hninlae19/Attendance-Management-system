<?php
// cron_ot_checkout.php
// This script checks out employees from their overtime 
// if they forgot to do so, setting the checkout time 
// to 5 minutes after their scheduled end time.

date_default_timezone_set('Asia/Yangon');
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/OvertimeAssign.php';

$overtimeModel = new OvertimeAssign();

// Run the auto checkout process
$overtimeModel->processAutoCheckouts();

echo "Overtime auto-checkout process completed successfully.\n";
