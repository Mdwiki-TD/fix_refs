<?php

namespace App\Fix\LangBots\BgBots;

class FixBg
{
    public static function bg_section(string $text, string $sourcetitle, int|string $mdwikiRevid): string
    {
        preg_match('/\{\{\s*Превод\s*от\s*\|/ui', $text, $ma);
        if (!empty($ma)) {
            return $text;
        }
        $temp = "{{Превод от|mdwiki|$sourcetitle|$mdwikiRevid}}\n";

        if (preg_match('/\[\[(Категория|Category):/ui', $text, $m, PREG_OFFSET_CAPTURE)) {
            $pos = (int)$m[0][1];
            $text = substr_replace($text, $temp, $pos, 0);
        } else {
            $text .= "\n" . $temp;
        }
        return $text;
    }

    public static function bg_fixes(string $text, string $sourcetitle, int|string $mdwikiRevid): string
    {
        $text = self::bg_section($text, $sourcetitle, $mdwikiRevid);

        $text = (string)preg_replace('/\[\[\s*(Категория|Category)\s*:\s*Translated from MDWiki\s*\]\]/ui', '', $text);
        return $text;
    }
}
