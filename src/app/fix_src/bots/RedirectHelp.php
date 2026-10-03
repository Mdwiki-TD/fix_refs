<?php

namespace App\fix_src\bots;

class RedirectHelp
{
    public static function page_is_redirect($title, $text)
    {
        if (preg_match('/^#(пренасочване|redirect)/i', $text)) {
            return true;
        }
        return false;
    }
}
