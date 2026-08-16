<?php
session_start();
date_default_timezone_set('Asia/Yangon');

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

require_once __DIR__ . '/core/HolidayHelper.php';

// Initialize the core Router
$router = new Router();

// Run background auto-checkout task
require_once __DIR__ . '/models/Attendance.php';
$attendanceModel = new Attendance();
$attendanceModel->processAutoCheckouts();
$attendanceModel->processFullDayAbsences();

require_once __DIR__ . '/models/OvertimeAssign.php';
$overtimeModel = new OvertimeAssign();
$overtimeModel->processNoShows();
$overtimeModel->processAutoCheckouts();
