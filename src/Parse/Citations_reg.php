<?php

namespace WpRefs\Parse\Reg_Citations;

/*
Usage:

use function WpRefs\Parse\Reg_Citations\get_name;
use function WpRefs\Parse\Reg_Citations\get_Reg_Citations;
use function WpRefs\Parse\Reg_Citations\get_full_refs;
use function WpRefs\Parse\Reg_Citations\getShortCitations;

*/

// include_once __DIR__ . '/../WikiParse/Citations_reg.php';

/**
 * Get the name attribute from citation options.
 *
 * @param string $options The citation options string to extract name from
 * @return string The extracted name or empty string if not found
 */

function get_name($options)
{
    if (trim($options) == "") {
        return "";
    }
    // $pa = "/name\s*=\s*\"(.*?)\"/i";
    $pa = "/name\s*\=\s*[\"\']*([^>\"\']*)[\"\']*\s*/iu";
    preg_match($pa, $options, $matches);
    // ---
    if (!isset($matches[1])) {
        return "";
    }
    $name = trim($matches[1]);
    return $name;
}
function get_Reg_Citations($text)
{
    preg_match_all("/<ref([^\/>]*?)>(.+?)<\/ref>/isu", $text, $matches);
    // ---
    $citations = [];
    // ---
    foreach ($matches[1] as $key => $citation_options) {
        $content = $matches[2][$key];
        $ref_tag = $matches[0][$key];
        $options = $citation_options;
        $citation = [
            "content" => $content,
            "tag" => $ref_tag,
            "name" => get_name($options),
            "options" => $options
        ];
        $citations[] = $citation;
    }

    return $citations;
}

function get_full_refs($text)
{
    $full = [];
    $citations = get_Reg_Citations($text);
    // ---
    foreach ($citations as $cite) {
        $name = $cite["name"];
        $ref = $cite["tag"];
        // ---
        $full[$name] = $ref;
    };
    // ---
    return $full;
}

function getShortCitations($text)
{
    preg_match_all("/<ref ([^\/>]*?)\/\s*>/isu", $text, $matches);
    // ---
    $citations = [];
    // ---
    foreach ($matches[1] as $key => $citation_options) {
        $ref_tag = $matches[0][$key];
        $options = $citation_options;
        $citation = [
            "content" => "",
            "tag" => $ref_tag,
            "name" => get_name($options),
            "options" => $options
        ];
        $citations[] = $citation;
    }

    return $citations;
}
