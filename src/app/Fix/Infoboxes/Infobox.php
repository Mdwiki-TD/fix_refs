<?php

namespace App\Fix\Infoboxes\Infobox;


use function App\Fix\Infoboxes\Infobox2\make_tempse;
use function App\Fix\Infoboxes\Infobox2\expend_new;

function find_max_value_key($dictionary)
{
    // Sort the dictionary by value in descending order
    arsort($dictionary);

    // Return the first key
    return key($dictionary);
}

function make_main_temp($tempseBy_u, $tempse)
{

    if (count($tempseBy_u) === 1) {
        return array_values($tempseBy_u)[0];
    }

    $mainTemp = [];

    # sort tempse by len of its value then get the first one
    $u2 = find_max_value_key($tempse);
    # ---
    $mainTemp = $tempseBy_u[$u2] ?? [];

    return $mainTemp;
}


function make_section_0($title, $newtext)
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
            // print_s("section_0 = newtext");
        }
    }

    return $section_0;
}


function fix_title_bold($text, $title)
{
    /*
    2020 2020
    */

    try {
        $title2 = preg_quote($title, '/');
    } catch (\Exception $e) {
        $title2 = $title;
    }

    $text = preg_replace("/\}\s*('''$title2''')/u", "}\n\n$1", $text);

    return $text;
}


function Expend_Infobox($text, $title, $section_0)
{

    $newtext = $text;

    if (!$section_0) {
        $section_0 = make_section_0($title, $newtext);
    }

    $newtext = fix_title_bold($newtext, $title);
    $section_0 = fix_title_bold($section_0, $title);

    $tab = make_tempse($section_0);

    $tempseBy_u = $tab["tempse_by_u"];
    $tempse = $tab["tempse"];

    $mainTemp = make_main_temp($tempseBy_u, $tempse);

    # work in main_temp:
    if (!empty($mainTemp)) {
        $mainTempText = $mainTemp["item"] ?? "";
        // $params = $mainTemp["params"] ?? [];

        $newTemp = expend_new($mainTempText);

        if ($newTemp !== $mainTempText) {
            $newtext = str_replace($mainTempText, $newTemp, $newtext);
            $newtext = str_replace($newTemp . "'''", $newTemp . "\n'''", $newtext);
        }
    }

    return $newtext;
}
