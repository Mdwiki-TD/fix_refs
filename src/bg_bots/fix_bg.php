<?php

namespace WpRefs\BG;
/*
usage:

use function WpRefs\BG\bg_fixes;

*/

use function WpRefs\TestBot\echo_test;
use function WpRefs\TestBot\echo_debug;

function bg_section($text, $sourcetitle, $mdwiki_revid)
{
    // ---
    // {{Превод от|mdwiki|Naproxen|1468415}}
    // if text has /\{\{\s*Превод\s*от\s*\|/ then return text
    preg_match('/\{\{\s*Превод\s*от\s*\|/ui', $text, $ma);
    // ---
    if (!empty($ma)) {
        return $text;
    }
    // ---
    $temp = "{{Превод от|mdwiki|$sourcetitle|$mdwiki_revid}}\n";
    // ---
    // add $temp before first match of "[[Категория:" or "[[Category:" and if there is no match then add it at the end
    if (preg_match('/\[\[(Категория|Category):/ui', $text, $m, PREG_OFFSET_CAPTURE)) {
        $pos = $m[0][1];
        $text = substr_replace($text, $temp, $pos, 0);
    } else {
        $text .= "\n" . $temp;
    }

    return $text;
}


function bg_fixes($text, $sourcetitle, $mdwiki_revid)
{
    // ---
    $text = bg_section($text, $sourcetitle, $mdwiki_revid);
    // ---
    // remove [[Category:Translated from MDWiki]]
    $text = preg_replace('/\[\[\s*(Категория|Category)\s*:\s*Translated from MDWiki\s*\]\]/ui', '', $text);
    // ---
    return $text;
}
