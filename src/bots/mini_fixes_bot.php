<?php

namespace WpRefs\Bots\Mini;
/*
usage:

use function WpRefs\Bots\Mini\mini_fixes;

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
    // ---
    // replace </ref> <ref> by </ref><ref
    $text = str_replace("</ref> <ref", "</ref><ref", $text);
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
