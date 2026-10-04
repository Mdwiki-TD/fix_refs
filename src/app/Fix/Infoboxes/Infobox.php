<?php

namespace App\Fix\Infoboxes;

use App\Fix\Infoboxes\Infobox2;

class Infobox
{
    /**
     * @param array<int|string, mixed> $dictionary
     * @return int|string|null
     */
    public static function find_max_value_key(array $dictionary): int|string|null
    {
        // Sort the dictionary by value in descending order
        arsort($dictionary);

        // Return the first key
        return key($dictionary);
    }

    /**
     * @param array<int|string, array<string, mixed>> $tempseBy_u
     * @param array<int|string, int> $tempse
     * @return array<string, mixed>
     */
    public static function make_main_temp(array $tempseBy_u, array $tempse): array
    {
        if (count($tempseBy_u) === 1) {
            return array_values($tempseBy_u)[0];
        }

        $mainTemp = [];

        # sort tempse by len of its value then get the first one
        $u2 = self::find_max_value_key($tempse);

        $mainTemp = $u2 !== null ? ($tempseBy_u[$u2] ?? []) : [];

        return $mainTemp;
    }

    public static function make_section_0(string $title, string $newtext): string
    {
        /*
        make_section_0
        */

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

    public static function fix_title_bold(string $text, string $title): string
    {
        /*
        2020 2020
        */

        try {
            $title2 = preg_quote($title, '/');
        } catch (\Exception $e) {
            $title2 = $title;
        }

        $text = (string)preg_replace("/\}\s*('''$title2''')/u", "}\n\n$1", $text);

        return $text;
    }

    public static function Expend_Infobox(string $text, string $title, string $section_0 = ""): string
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

        # work in main_temp:
        if (!empty($mainTemp)) {
            $mainTempText = is_string($mainTemp["item"] ?? null) ? $mainTemp["item"] : "";

            $newTemp = Infobox2::expend_new($mainTempText);

            if ($newTemp !== $mainTempText) {
                $newtext = str_replace($mainTempText, $newTemp, $newtext);
                $newtext = str_replace($newTemp . "'''", $newTemp . "\n'''", $newtext);
            }
        }

        return $newtext;
    }
}
