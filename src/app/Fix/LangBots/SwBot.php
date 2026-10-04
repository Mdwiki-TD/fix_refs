<?php

namespace App\Fix\LangBots;

class SwBot
{
    public static function sw_fixes(string $text): string
    {
        // find == Marejeleo == replace by == Marejeo ==
        $text = (string)preg_replace('/(=+)\s*Marejeleo\s*(\1)/iu', '\1 Marejeo \1', $text);
        return $text;
    }
}
