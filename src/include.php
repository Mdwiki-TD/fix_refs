<?php

if (!empty($_GET['test'] ?? $_POST['test'] ?? '') || ($_SERVER['SERVER_NAME'] ?? '') == 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include_once __DIR__ . '/app/Settings.php';
include_once __DIR__ . '/app/csrf.php';
include_once __DIR__ . '/app/run.php';
include_once __DIR__ . '/app/wikibots/wikitext.php';
include_once __DIR__ . '/app/fix_src/include_files.php';
