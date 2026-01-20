<?php
include_once __DIR__ . '/test_bot.php';

include_once __DIR__ . '/WikiParse/include_it.php';

foreach (glob(__DIR__ . "/helps_bots/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/infoboxes/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/Parse/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/bots/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/es_bots/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/pt_bots/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/pl_bots/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/bg_bots/*.php") as $filename) {
    include_once $filename;
}

include_once __DIR__ . '/sw.php';
include_once __DIR__ . '/md_cat.php';
// include_once __DIR__ . '/es.php';
include_once __DIR__ . '/index.php';
