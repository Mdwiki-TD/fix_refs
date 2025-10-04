<?php

namespace WpRefs\EsBots\Section;
/*

usage:

use function WpRefs\EsBots\Section\es_section;

*/

function es_section($sourcetitle, $text, $mdwiki_revid)
{
    // Replace old template with new one
    // replace ({{Traducido ref|mdwiki|) with ({{Traducido ref MDWiki|en|)
    $text = preg_replace(
        '/\{\{\s*Traducido\s*ref\s*\|\s*mdwiki\s*\|/iu',
        "{{Traducido ref MDWiki|en|",
        $text
    );

    // If template already exists (any variant), return as-is
    if (preg_match('/\{\{\s*Traducido\s*ref(?:\s*MDWiki)?\s*\|/iu', $text)) {
        return $text;
    }

    $date = "{{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}";

    // $temp = "{{Traducido ref|mdwiki|$sourcetitle|oldid=$mdwiki_revid|trad=|fecha=$date}}";
    $temp = "{{Traducido ref MDWiki|en|$sourcetitle|oldid=$mdwiki_revid|trad=|fecha=$date}}";


    // Insert after "== Enlaces externos ==" if it exists, otherwise append
    if (preg_match('/==\s*Enlaces\s*externos\s*==/iu', $text)) {
        $text = preg_replace(
            '/(==\s*Enlaces\s*externos\s*==)/iu',
            "$1\n$temp\n",
            $text,
            1
        );
    } else {
        $text .= "\n== Enlaces externos ==\n$temp\n";
    }

    return $text;
}
