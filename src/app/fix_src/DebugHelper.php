<?php

namespace App\fix_src;

class DebugHelper
{
    public static function echo_test($str)
    {
        $test = $_POST['test'] ?? $_GET['test'] ?? '';
        if (!empty($test)) {
            echo $str . "\n";
        }
    }

    public static function echo_debug($str)
    {
        if (defined('DEBUG')) {
            echo $str . "\n";
        }
    }
}
