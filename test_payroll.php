<?php
require 'config/database.php';
require 'models/Payroll.php';
$p = new Payroll();
echo $p->generatePayroll(8, 2026);
