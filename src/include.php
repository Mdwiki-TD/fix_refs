<?php

if (!empty($_GET['test'] ?? $_POST['test'] ?? '') || ($_SERVER['SERVER_NAME'] ?? '') == 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include_once __DIR__ . '/app/Settings.php';
include_once __DIR__ . '/app/Csrf.php';
include_once __DIR__ . '/app/Run.php';
include_once __DIR__ . '/app/Wikibots/Wikitext.php';
include_once __DIR__ . '/app/Fix/include_files.php';
