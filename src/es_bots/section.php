<?php

namespace WpRefs\EsBots\Section;
/*

usage:

use function WpRefs\EsBots\Section\es_section;

*/

function es_section($sourcetitle, $text, $mdwiki_revid)
{
    // ---
    // if text has /\{\{\s*Traducido ref\s*\|/ then return text
    preg_match('/\{\{\s*Traducido\s*ref( mdwiki|)\s*\|/i', $text, $ma);
    if (!empty($ma)) {
        // pub_test_print("return text;");
        return $text;
    }
    // ---
    $date = "{{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}";
    // ---
    // $temp = "{{Traducido ref|mdwiki|$sourcetitle|oldid=$mdwiki_revid|trad=|fecha=$date}}";
    $temp = "{{Traducido ref MDWiki|en|$sourcetitle|oldid=$mdwiki_revid|trad=|fecha=$date}}";
    // ---
    // find /==\s*Enlaces\s*externos\s*==/ in text if exists add temp after it
    // if not exists add temp at the end of text
    // ---
    preg_match('/==\s*Enlaces\s*externos\s*==/i', $text, $matches);
    // ---
    if (!empty($matches)) {
        $text = preg_replace('/(==\s*Enlaces\s*externos\s*==)/i', "$1\n$temp\n", $text, 1);
    } else {
        $text .= "\n== Enlaces externos ==\n$temp\n";
    }
    // ---
    return $text;
}


$old = "  ==   Enlaces externos   ==  ";

echo json_encode([1 => es_section("test!", $old, 520)]);
