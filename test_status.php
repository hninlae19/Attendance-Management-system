<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/LeaveRequest.php';

$lr = new LeaveRequest();
try {
    $res = $lr->updateStatus(1, 'approve');
    var_dump($res);
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
