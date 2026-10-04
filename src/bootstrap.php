<?php

if (!empty($_GET['test'] ?? $_POST['test'] ?? '') || ($_SERVER['SERVER_NAME'] ?? '') == 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once __DIR__ . '/app/autoload.php';

include_once __DIR__ . '/app/Settings.php';
include_once __DIR__ . '/app/Logger.php';
include_once __DIR__ . '/app/Csrf.php';
include_once __DIR__ . '/app/Run.php';
include_once __DIR__ . '/app/Wikibots/Wikitext.php';

# WikiParse

foreach (glob(__DIR__ . "/app/Fix/WikiParse/DataModel/*.php") as $filename) {
    include_once $filename;
}
foreach (glob(__DIR__ . "/app/Fix/WikiParse/*.php") as $filename) {
    include_once $filename;
}

# HelpsBots

$folders = [
    "HelpsBots",
    "Infoboxes",
    "Parse",
    "Bots",
    "LangBots",
];

foreach ($folders as $folder) {
    foreach (glob(__DIR__ . "/app/Fix/$folder/*.php") as $filename) {
        include_once $filename;
    }
}

# include sub folder in LangBots
foreach (glob(__DIR__ . "/app/Fix/LangBots/*/") as $subfolder) {
    foreach (glob($subfolder . "*.php") as $filename) {
        include_once $filename;
    }
}

include_once __DIR__ . '/app/Fix/MdCat.php';
include_once __DIR__ . '/app/Fix/Index.php';
