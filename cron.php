<?php
// Simple CLI wrapper for the CronController

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

$_GET['url'] = 'cron/run';
require_once __DIR__ . '/index.php';
