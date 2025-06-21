<?php

namespace WpRefs\Bots\Sections;
/*
usage:

use function WpRefs\Bots\Sections\fix_sections_titles;

*/

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
            $new_text = str_replace("== $key ==", "== $value ==", $new_text);
        }
    }
    // ---
    return $new_text;
}
