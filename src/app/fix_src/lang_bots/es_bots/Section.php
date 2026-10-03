<?php

namespace App\fix_src\lang_bots\es_bots;

class Section
{
    public static function es_section($sourcetitle, $text, $mdwikiRevid)
    {
        $text = preg_replace(
            '/\{\{\s*Traducido\s*ref\s*\|\s*mdwiki\s*\|/iu',
            "{{Traducido ref MDWiki|en|",
            $text
        );

        if (preg_match('/\{\{\s*Traducido\s*ref(?:\s*MDWiki)?\s*\|/iu', $text)) {
            return $text;
        }

        $date = "{{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}";
        $temp = "{{Traducido ref MDWiki|en|$sourcetitle|oldid=$mdwikiRevid|trad=|fecha=$date}}";

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
}
