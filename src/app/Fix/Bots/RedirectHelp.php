<?php

namespace App\Fix\Bots;

class RedirectHelp
{
    public static function page_is_redirect(string $title, string $text): bool
    {
        if (preg_match('/^#(пренасочване|redirect)/', $text)) {
            return true;
        }
        return false;
    }
}
