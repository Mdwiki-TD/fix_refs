<?php

if (!empty($_GET['test'] ?? $_POST['test'] ?? '') || $_SERVER['SERVER_NAME'] == 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include_once __DIR__ . '/Settings.php';
include_once __DIR__ . '/fix_src/include_files.php';
