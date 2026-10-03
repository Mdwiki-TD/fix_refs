<?php

namespace App;

class DebugHelper
{
    public static function debug($str)
    {
        $test = $_POST['test'] ?? $_GET['test'] ?? '';
        if (!empty($test)) {
            echo $str . "\n";
        }
    }

    public static function debug($str)
    {
        if (defined('DEBUG')) {
            echo $str . "\n";
        }
    }
}
