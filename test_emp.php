<?php
require 'config/database.php';
require 'models/Attendance.php';
$model = new Attendance();
$records = $model->getAllRecords();
echo count($records) . " records\n";
print_r(array_slice($records, 0, 1));
