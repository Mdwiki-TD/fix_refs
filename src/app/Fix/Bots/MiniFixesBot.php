<?php

namespace App\Fix\Bots;

class MiniFixesBot
{
    public static function fix_sections_titles(string $text, string $lang): string
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
            // Quote the key to avoid regex special characters
            $k = preg_quote($key, '/');

            // Regex pattern explanation:
            // (1) (={1,})   -> capture one or more '=' at the beginning
            // (2) \s*       -> optional spaces
            // (3) $k        -> the key to be replaced
            // (4) [^=]*     -> any extra text (e.g. numbers) except '='
            // (5) \s* \1    -> optional spaces and same '=' count at the end
            $pattern = '/(=+)\s*' . $k . '\s*\1/iu';

            // Replacement keeps the same '=' count but replaces the key
            $replacement = '$1 ' . $value . ' $1';

            $text = (string)preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

    public static function remove_space_before_ref_tags(string $text, string $lang): string
    {

        $forLangs = ["sw", "bn", "ar"];

        // if (in_array($lang, $forLangs)) {
        $text = (string)preg_replace("/\s*(\.|,|。|।)\s*<ref/iu", "$1<ref", $text);
        // }

        return $text;
    }

    public static function refs_tags_spaces(string $text): string
    {
        // Remove spaces between reference tags more precisely
        // $text = preg_replace('/(<\/ref>)\s+(<ref[^>]*>)/', '$1$2', $text);

        // </ref> <ref>
        $text = (string)preg_replace("/<\/ref>\s*<ref/u", "</ref><ref", $text);

        // <ref name="A Costa"/><ref name=Gaia>
        $text = (string)preg_replace("/\/>\s*<ref/u", "/><ref", $text);

        // </ref><ref name=... | </ref><ref>
        $text = str_replace("</ref> <ref", "</ref><ref", $text);


        $text = str_replace("> <ref", "><ref", $text);

        return $text;
    }

    public static function fix_preffix(string $text, string $lang): string
    {
        // [[:en:X-сцепленное_рецессивное_наследование|Х-сцепленным рецессивным]], [[:ru:Спинальная_мышечная_атрофия|аутосомно-доминантным]]

        // replace [[:{en}: by [[
        $text = (string)preg_replace('/\[\[:en:/u', "[[", $text);
        // replace [[:{lang}: by [[
        $text = (string)preg_replace('/\[\[:' . preg_quote($lang, '/') . ':/ui', "[[", $text);

        return $text;
    }

    public static function remove_template_rtt_links(string $text): string
    {
        // Remove wiki links to Template:RTT like [[Template:RTT|සැකිල්ල:RTT]]
        // Use [^\[\]]+ to match the second part (anything except square brackets)
        $text = (string)preg_replace('/\[\[Template:RTT\|[^\[\]]+\]\]/u', '', $text);

        return $text;
    }

    public static function mini_fixes_after_fixing(string $text, string $lang): string
    {
        // remove empty lines
        $text = (string)preg_replace('/^\s*\n/mu', "\n", $text);
        $text = self::fix_preffix($text, $lang);
        return $text;
    }

    public static function mini_fixes(string $text, string $lang): string
    {
        $text = self::refs_tags_spaces($text);
        $text = self::fix_sections_titles($text, $lang);
        $text = self::remove_space_before_ref_tags($text, $lang);
        $text = self::remove_template_rtt_links($text);
        return $text;
    }
}
