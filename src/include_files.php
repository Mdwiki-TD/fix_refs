<?php
include_once __DIR__ . '/test_bot.php';

include_once __DIR__ . '/WikiParse/include_it.php';

include_once __DIR__ . '/en_lang_param.php';
include_once __DIR__ . '/mv_dots.php';

foreach (glob(__DIR__ . "/infoboxes/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/Parse/*.php") as $filename) {
    include_once $filename;
}

foreach (glob(__DIR__ . "/bots/*.php") as $filename) {
    include_once $filename;
}

include_once __DIR__ . '/sw.php';
include_once __DIR__ . '/md_cat.php';
include_once __DIR__ . '/es.php';
include_once __DIR__ . '/index.php';
