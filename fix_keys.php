<?php
$files = [
    'c:\wamp64\www\payrollsystem\views\employee\dashboard.php',
    'c:\wamp64\www\payrollsystem\views\admin\employees.php',
    'c:\wamp64\www\payrollsystem\views\admin\attendance.php',
    'c:\wamp64\www\payrollsystem\views\admin\employee_details.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace("position_name", "PositionName", $content);
        file_put_contents($file, $content);
    }
}
echo "Done.";
