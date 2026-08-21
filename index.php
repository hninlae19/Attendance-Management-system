<?php
session_start();
date_default_timezone_set('Asia/Yangon');

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

require_once __DIR__ . '/core/HolidayHelper.php';
require_once __DIR__ . '/models/Attendance.php';

// Run background auto-checkout and absence processing tasks
$attendanceModel = new Attendance();
$attendanceModel->processAutoCheckouts();
$attendanceModel->processFullDayAbsences();

// Initialize the core Router
$router = new Router();



