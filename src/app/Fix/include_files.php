<?php
include_once __DIR__ . '/DebugHelper.php';

include_once __DIR__ . '/WikiParse/include_it.php';

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
