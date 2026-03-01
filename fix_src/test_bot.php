<?php

namespace WpRefs\TestBot;

/*
usage:

use function WpRefs\TestBot\echo_test;
use function WpRefs\TestBot\echo_debug;

*/

function echo_test($str)
{
    // ---
    $test = $_POST['test'] ?? $_GET['test'] ?? '';
    // ---
    // if (isset($_POST['test']) || isset($_GET['test'])) {
    if (!empty($test)) {
        echo $str . "\n";
    }
    // ---
}

function echo_debug($str)
{
    // ---
    if (defined('DEBUG')) {
        echo $str . "\n";
    }
    // ---
}
