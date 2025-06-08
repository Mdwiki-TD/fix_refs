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
    if (isset($_POST['test']) || isset($_GET['test'])) {
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
