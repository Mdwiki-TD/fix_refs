<?php

namespace WpRefs\Bots\Mini;
/*
usage:

use function WpRefs\Bots\Mini\mini_fixes;
use function WpRefs\Bots\Mini\mini_fixes_after_fixing;

*/

function remove_space_before_ref_tags($text, $lang)
{
    // ---
    $new_text = $text;
    // ---
    // find == Marejeleo == replace by == Marejeo ==
    // $new_text = preg_replace('/==\s*Marejeleo\s*==/i', '== Marejeo ==', $new_text);
    // ---
    $to_replace = [
        "sw" => [
            "Marejeleo" => "Marejeo"
        ],
        // Примечания" (references) instead of "Ссылки" (links) for the heading of the reference section
        "ru" => [
            "Ссылки" => "Примечания"
        ]
    ];
    // ---
    if (array_key_exists($lang, $to_replace)) {
        foreach ($to_replace[$lang] as $key => $value) {
            // $new_text = preg_replace("/==\s*$key\s*==/i", "== $value ==", $new_text);
            $new_text = preg_replace("/==\s*" . preg_quote($key, '/') . "\s*==/i", "== $value ==", $new_text);
        }
    }
    // ---
    return $new_text;
}

function fix_sections_titles($text, $lang)
{
    // ---
    $new_text = $text;
    // ---
    // find == Marejeleo == replace by == Marejeo ==
    // $new_text = preg_replace('/==\s*Marejeleo\s*==/i', '== Marejeo ==', $new_text);
    // ---
    $to_replace = [
        "sw" => [
            "Marejeleo" => "Marejeo"
        ],
        // Примечания" (references) instead of "Ссылки" (links) for the heading of the reference section
        "ru" => [
            "Ссылки" => "Примечания"
        ]
    ];
    // ---
    if (array_key_exists($lang, $to_replace)) {
        foreach ($to_replace[$lang] as $key => $value) {
            // $new_text = preg_replace("/==\s*$key\s*==/i", "== $value ==", $new_text);
            $new_text = preg_replace("/==\s*" . preg_quote($key, '/') . "\s*==/i", "== $value ==", $new_text);
        }
    }
    // ---
    return $new_text;
}

function refs_tags_spaces($text)
{
    // Remove spaces between reference tags more precisely
    // $text = preg_replace('/(<\/ref>)\s+(<ref[^>]*>)/', '$1$2', $text);

    // </ref> <ref>
    $text = preg_replace("/<\/ref>\s*<ref/", "</ref><ref", $text);

    // <ref name="A Costa"/><ref name=Gaia>
    $text = preg_replace("/\/>\s*<ref/", "/><ref", $text);

    // </ref><ref name=... | </ref><ref>
    $text = str_replace("</ref> <ref", "</ref><ref", $text);

    // ---
    $text = str_replace("> <ref", "><ref", $text);
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


function fix_preffix($text, $lang)
{
    // [[:en:X-сцепленное_рецессивное_наследование|Х-сцепленным рецессивным]], [[:ru:Спинальная_мышечная_атрофия|аутосомно-доминантным]]
    // ---
    // replace [[:{en}: by [[
    $text = preg_replace('/\[\[:en:/', "[[", $text);
    // replace [[:{lang}: by [[
    $text = preg_replace('/\[\[:' . preg_quote($lang, '/') . ':/', "[[", $text);
    // ---
    return $text;
}

function mini_fixes_after_fixing($text, $lang)
{
    // ---
    // remove empty lines
    $text = preg_replace('/^\s*\n/m', "\n", $text);
    // ---
    $text = fix_preffix($text, $lang);
    // ---
    return $text;
}
