<?php

namespace App\Fix\LangBots\EsBots;

class Section
{
    public static function es_section(string $sourcetitle, string $text, int|string $mdwikiRevid): string
    {
        // Replace old template with new one
        $text = (string)preg_replace(
            '/\{\{\s*Traducido\s*ref\s*\|\s*mdwiki\s*\|/iu',
            "{{Traducido ref MDWiki|en|",
            $text
        );

        // If template already exists (any variant), return as-is
        if (preg_match('/\{\{\s*Traducido\s*ref(?:\s*MDWiki)?\s*\|/iu', $text)) {
            return $text;
        }

        $date = "{{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}";

        $temp = "{{Traducido ref MDWiki|en|$sourcetitle|oldid=$mdwikiRevid|trad=|fecha=$date}}";

        // Insert after "== Enlaces externos ==" if it exists, otherwise append
        if (preg_match('/==\s*Enlaces\s*externos\s*==/iu', $text)) {
            $text = (string)preg_replace(
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
