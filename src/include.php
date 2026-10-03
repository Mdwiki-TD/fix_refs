<?php

if (!empty($_GET['test'] ?? $_POST['test'] ?? '') || ($_SERVER['SERVER_NAME'] ?? '') == 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once __DIR__ . '/app/autoload.php';
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}
