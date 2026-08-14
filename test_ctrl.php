<?php
session_start();
$_SESSION['employee_id'] = 2; // user
$_SESSION['user_id'] = 2;
$_SESSION['role'] = 'Employee';
$_SESSION['csrf_token'] = 'test';
$_POST['csrf_token'] = 'test';
$_POST['action'] = 'apply';
$_POST['leave_type_id'] = 1;
$_POST['start_date'] = '2026-08-20';
$_POST['end_date'] = '2026-08-21';
$_POST['reason'] = 'Test Application Form';
$_SERVER['REQUEST_METHOD'] = 'POST';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/controllers/EmployeeController.php';

// Mock redirect to avoid exiting
class TestController extends EmployeeController {
    public function redirect($url) {
        echo "Redirecting to: " . $url . "\n";
        exit;
    }
}

$c = new TestController();
$c->leaves();
?>
