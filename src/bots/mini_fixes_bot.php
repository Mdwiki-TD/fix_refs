<?php

namespace WpRefs\Bots\Mini;
/*
usage:

use function WpRefs\Bots\Mini\mini_fixes;
use function WpRefs\Bots\Mini\fix_sections_titles;
use function WpRefs\Bots\Mini\mini_fixes_after_fixing;

*/

function fix_sections_titles($text, $lang)
{
    $to_replace = [
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

    if (! array_key_exists($lang, $to_replace)) {
        return $text;
    }

    foreach ($to_replace[$lang] as $key => $value) {
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

        $text = preg_replace($pattern, $replacement, $text);
    }

    return $text;
}

function remove_space_before_ref_tags($text, $lang)
{
    // ---
    $for_langs = ["sw", "bn", "ar"];
    // ---
    // if (in_array($lang, $for_langs)) {
    $text = preg_replace("/\s*(\.|,|。|।)\s*<ref/iu", "$1<ref", $text);
    // }
    // ---
    return $text;
}

function refs_tags_spaces($text)
{
    // Remove spaces between reference tags more precisely
    // $text = preg_replace('/(<\/ref>)\s+(<ref[^>]*>)/', '$1$2', $text);

    // </ref> <ref>
    $text = preg_replace("/<\/ref>\s*<ref/u", "</ref><ref", $text);

    // <ref name="A Costa"/><ref name=Gaia>
    $text = preg_replace("/\/>\s*<ref/u", "/><ref", $text);

    // </ref><ref name=... | </ref><ref>
    $text = str_replace("</ref> <ref", "</ref><ref", $text);

    // ---
    $text = str_replace("> <ref", "><ref", $text);
    // ---
    return $text;
}

function fix_preffix($text, $lang)
{
    // [[:en:X-сцепленное_рецессивное_наследование|Х-сцепленным рецессивным]], [[:ru:Спинальная_мышечная_атрофия|аутосомно-доминантным]]
    // ---
    // replace [[:{en}: by [[
    $text = preg_replace('/\[\[:en:/u', "[[", $text);
    // replace [[:{lang}: by [[
    $text = preg_replace('/\[\[:' . preg_quote($lang, '/') . ':/ui', "[[", $text);
    // ---
    return $text;
}

function mini_fixes_after_fixing($text, $lang)
{
    // ---
    // remove empty lines
    $text = preg_replace('/^\s*\n/mu', "\n", $text);
    // ---
    $text = fix_preffix($text, $lang);
    // ---
    return $text;
}

function mini_fixes($text, $lang)
{
    // ---
    $text = refs_tags_spaces($text);
    // ---
    $text = fix_sections_titles($text, $lang);
    // ---
    $text = remove_space_before_ref_tags($text, $lang);
    // ---
    return $text;
}
