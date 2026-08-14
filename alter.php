<?php
$db = new PDO('mysql:host=localhost;dbname=payrolldb', 'root', '');
$db->exec("ALTER TABLE LeaveTypes ADD COLUMN DurationMonths INT NOT NULL DEFAULT 0;");
echo "Done";
?>
