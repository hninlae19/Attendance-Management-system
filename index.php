<?php
session_start();
date_default_timezone_set('Asia/Yangon');

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

// Initialize the core Router
$router = new Router();
