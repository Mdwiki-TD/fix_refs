<?php
include_once __DIR__ . '/test_bot.php';

include_once __DIR__ . '/WikiParse/include_it.php';
$folders = [
    "helps_bots",
    "infoboxes",
    "Parse",
    "bots",
    "lang_bots",
];

foreach ($folders as $folder) {
    foreach (glob(__DIR__ . "/$folder/*.php") as $filename) {
        include_once $filename;
    }
}

# include sub folder in lang_bots
foreach (glob(__DIR__ . "/lang_bots/*/") as $subfolder) {
    foreach (glob($subfolder . "*.php") as $filename) {
        include_once $filename;
    }
}

include_once __DIR__ . '/md_cat.php';
include_once __DIR__ . '/index.php';
