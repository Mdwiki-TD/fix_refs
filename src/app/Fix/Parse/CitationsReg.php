<?php

namespace App\Parse\Reg_Citations;

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
function get_regex_citations($text)
{
    preg_match_all("/<ref([^\/>]*?)>(.+?)<\/ref>/isu", $text, $matches);
    // ---
    $citations = [];
    // ---
    foreach ($matches[1] as $key => $citationOptions) {
        $content = $matches[2][$key];
        $refTag = $matches[0][$key];
        $options = $citationOptions;
        $citation = [
            "content" => $content,
            "tag" => $refTag,
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
    $citations = get_regex_citations($text);
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

function get_short_citations($text)
{
    preg_match_all("/<ref ([^\/>]*?)\/\s*>/isu", $text, $matches);
    // ---
    $citations = [];
    // ---
    foreach ($matches[1] as $key => $citationOptions) {
        $refTag = $matches[0][$key];
        $options = $citationOptions;
        $citation = [
            "content" => "",
            "tag" => $refTag,
            "name" => get_name($options),
            "options" => $options
        ];
        $citations[] = $citation;
    }

    return $citations;
}
