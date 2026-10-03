<?php

namespace App\Fix\LangBots\BgBots;

class FixBg
{
    public static function bg_section($text, $sourcetitle, $mdwikiRevid)
    {
        preg_match('/\{\{\s*Превод\s*от\s*\|/ui', $text, $ma);
        if (!empty($ma)) {
            return $text;
        }
        $temp = "{{Превод от|mdwiki|$sourcetitle|$mdwikiRevid}}\n";
        if (preg_match('/\[\[(Категория|Category):/ui', $text, $m, PREG_OFFSET_CAPTURE)) {
            $pos = $m[0][1];
            $text = substr_replace($text, $temp, $pos, 0);
        } else {
            $text .= "\n" . $temp;
        }
        return $text;
    }

    public static function bg_fixes($text, $sourcetitle, $mdwikiRevid)
    {
        $text = self::bg_section($text, $sourcetitle, $mdwikiRevid);
        $text = preg_replace('/\[\[\s*(Категория|Category)\s*:\s*Translated from MDWiki\s*\]\]/ui', '', $text);
        return $text;
    }
}
