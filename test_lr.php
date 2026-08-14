<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/LeaveRequest.php';

$lr = new LeaveRequest();
$lr->LeaveTypeID = 1;
$lr->EmpID = 2; // user's EmpID is 2
$lr->StartDate = '2026-08-20';
$lr->EndDate = '2026-08-21';
$lr->Reason = 'Test Reason';
$lr->Status = 'Pending';

$result = $lr->create();
var_dump($result);

$db = new Database();
$conn = $db->getConnection();
var_dump($conn->errorInfo());
?>
