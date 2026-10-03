<?php

namespace App\Fix\lang_bots;

class SwBot
{
    public static function sw_fixes($text)
    {
        $text = preg_replace('/(=+)\s*Marejeleo\s*(\1)/iu', '\1 Marejeo \1', $text);
        return $text;
    }
}
