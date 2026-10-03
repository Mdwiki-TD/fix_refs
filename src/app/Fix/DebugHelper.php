<?php

namespace WpRefs\TestBot;

function echo_test($str)
{
    $test = $_POST['test'] ?? $_GET['test'] ?? '';
    if (!empty($test)) {
        echo $str . "\n";
    }
}

function echo_debug($str)
{
    if (defined('DEBUG')) {
        echo $str . "\n";
    }
}
