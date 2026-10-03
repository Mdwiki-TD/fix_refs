<?php
include_once __DIR__ . '/DebugHelper.php';

# WikiParse

foreach (glob(__DIR__ . "/WikiParse/DataModel/*.php") as $filename) {
    include_once $filename;
}
foreach (glob(__DIR__ . "/WikiParse/*.php") as $filename) {
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
    foreach (glob(__DIR__ . "/$folder/*.php") as $filename) {
        include_once $filename;
    }
}

# include sub folder in LangBots
foreach (glob(__DIR__ . "/LangBots/*/") as $subfolder) {
    foreach (glob($subfolder . "*.php") as $filename) {
        include_once $filename;
    }
}

include_once __DIR__ . '/MdCat.php';
include_once __DIR__ . '/Index.php';
