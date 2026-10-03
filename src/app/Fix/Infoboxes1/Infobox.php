<?php

namespace App\Fix\Infoboxes;

class Infobox
{
    public static function find_max_value_key($dictionary)
    {
        arsort($dictionary);
        return key($dictionary);
    }

    public static function make_main_temp($tempseBy_u, $tempse)
    {
        if (count($tempseBy_u) === 1) {
            return array_values($tempseBy_u)[0];
        }
        $mainTemp = [];
        $u2 = self::find_max_value_key($tempse);
        $mainTemp = $tempseBy_u[$u2] ?? [];
        return $mainTemp;
    }

    public static function make_section_0($title, $newtext)
    {
        $section_0 = "";
        if (strpos($newtext, "==") !== false) {
            $section_0 = explode("==", $newtext)[0];
        } else {
            $tagg = "'''" . $title . "'''";
            if (strpos($newtext, $tagg) !== false) {
                $section_0 = explode($tagg, $newtext)[0];
            } else {
                $section_0 = $newtext;
            }
        }
        return $section_0;
    }

    public static function fix_title_bold($text, $title)
    {
        try {
            $title2 = preg_quote($title, '/');
        } catch (\Exception $e) {
            $title2 = $title;
        }
        $text = preg_replace("/\}\s*('''$title2''')/u", "}\n\n$1", $text);
        return $text;
    }

    public static function Expend_Infobox($text, $title, $section_0)
    {
        $newtext = $text;
        if (!$section_0) {
            $section_0 = self::make_section_0($title, $newtext);
        }
        $newtext = self::fix_title_bold($newtext, $title);
        $section_0 = self::fix_title_bold($section_0, $title);

        $tab = Infobox2::make_tempse($section_0);

        $tempseBy_u = $tab["tempse_by_u"];
        $tempse = $tab["tempse"];

        $mainTemp = self::make_main_temp($tempseBy_u, $tempse);

        if (!empty($mainTemp)) {
            $mainTempText = $mainTemp["item"] ?? "";
            $newTemp = Infobox2::expend_new($mainTempText);
            if ($newTemp !== $mainTempText) {
                $newtext = str_replace($mainTempText, $newTemp, $newtext);
                $newtext = str_replace($newTemp . "'''", $newTemp . "\n'''", $newtext);
            }
        }

        return $newtext;
    }
}
