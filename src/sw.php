<?php

namespace WpRefs\SW;
/*

usage:

use function WpRefs\SW\sw_fixes;

*/

function sw_fixes($text)
{
    // ---
    // find == Marejeleo == replace by == Marejeo ==
    $text = preg_replace('/(=+)\s*Marejeleo\s*(\1)/i', '\1 Marejeo \1', $text);
    // ---
    return $text;
}
