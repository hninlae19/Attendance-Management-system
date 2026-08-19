<?php
require 'c:/wamp64/www/payrollsystem/models/Attendance.php';
$att = new Attendance();
var_dump($att->calculateStatus(null, '17:15:00'));
?>
