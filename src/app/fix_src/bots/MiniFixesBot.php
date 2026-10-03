<?php

namespace App\fix_src\bots;

class MiniFixesBot
{
    public static function fix_sections_titles($text, $lang)
    {
        $toReplace = [
            "hr" => [
                "Reference" => "Izvori",
                "References" => "Izvori",
            ],
            "sw" => [
                "Reference" => "Marejeo",
                "References" => "Marejeo",
                "Marejeleo" => "Marejeo"
            ],
            "ru" => [
                "Reference" => "Примечания",
                "References" => "Примечания",
                "Ссылки" => "Примечания"
            ]
        ];

        if (!array_key_exists($lang, $toReplace)) {
            return $text;
        }

        foreach ($toReplace[$lang] as $key => $value) {
            $k = preg_quote($key, '/');
            $pattern = '/(=+)\s*' . $k . '\s*\1/iu';
            $replacement = '$1 ' . $value . ' $1';
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

    public static function remove_space_before_ref_tags($text, $lang)
    {
        $text = preg_replace("/\s*(\.|,|。|।)\s*<ref/iu", "$1<ref", $text);
        return $text;
    }

    public static function refs_tags_spaces($text)
    {
        $text = preg_replace("/<\/ref>\s*<ref/u", "</ref><ref", $text);
        $text = preg_replace("/\/>\s*<ref/u", "/><ref", $text);
        $text = str_replace("</ref> <ref", "</ref><ref", $text);
        $text = str_replace("> <ref", "><ref", $text);
        return $text;
    }

    public static function fix_preffix($text, $lang)
    {
        $text = preg_replace('/\[\[:en:/u', "[[", $text);
        $text = preg_replace('/\[\[:' . preg_quote($lang, '/') . ':/ui', "[[", $text);
        return $text;
    }

    public static function remove_template_rtt_links($text)
    {
        $text = preg_replace('/\[\[Template:RTT\|[^\[\]]+\]\]/u', '', $text);
        return $text;
    }

    public static function mini_fixes_after_fixing($text, $lang)
    {
        $text = preg_replace('/^\s*\n/mu', "\n", $text);
        $text = self::fix_preffix($text, $lang);
        return $text;
    }

    public static function mini_fixes($text, $lang)
    {
        $text = self::refs_tags_spaces($text);
        $text = self::fix_sections_titles($text, $lang);
        $text = self::remove_space_before_ref_tags($text, $lang);
        $text = self::remove_template_rtt_links($text);
        return $text;
    }
}
