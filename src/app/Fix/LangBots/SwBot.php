<?php

namespace App\SW;


function sw_fixes($text)
{
    // ---
    // find == Marejeleo == replace by == Marejeo ==
    $text = preg_replace('/(=+)\s*Marejeleo\s*(\1)/iu', '\1 Marejeo \1', $text);
    // ---
    return $text;
}
