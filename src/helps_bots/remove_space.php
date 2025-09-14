<?php

namespace WpRefs\RemoveSpace;

// print errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
usage:

use function WpRefs\RemoveSpace\remove_spaces_between_last_word_and_beginning_of_ref;

*/
// ---
// define("DEBUG", true);

use function WpRefs\TestBot\echo_debug;

if (!function_exists('str_ends_with')) {
    function str_ends_with($string, $endString)
    {
        $len = strlen($endString);
        return substr($string, -$len) === $endString;
    }
}
if (!function_exists('str_starts_with')) {
    function str_starts_with($text, $start)
    {
        return strpos($text, $start) === 0;
    }
}

function match_it($text, $charters)
{
    $pattern = '/(<\/ref>|\/>)\s*([' . preg_quote($charters, '/') . ']\s*)$/u';
    if (preg_match($pattern, $text, $m)) {
        return $m[2];
    }
    return null;
}

function get_parts($newtext, $charters)
{
    $matches = explode("\n\n", $newtext);
    // ---
    if (count($matches) == 1) {
        $matches = explode("\r\n\r\n", $newtext);
    }
    // ---
    echo_debug("count(matches)=" . count($matches) . "\n");
    // ---
    $new_parts = [];
    // ---
    foreach ($matches as $p) {
        $chart = match_it($p, $charters);
        if ($chart) {
            $new_parts[] = [$p, $chart];
        }
    }
    // ---
    echo_debug("count(new_parts)=" . count($new_parts) . "\n");
    // ---
    return $new_parts;
}


function remove_spaces_between_last_word_and_beginning_of_ref($newtext, $lang)
{

    // --- 1) تحديد علامات الترقيم
    $dot = "\.,。।";

    if ($lang === "hy") {
        $dot = "\.,。।։";
    }

    $parts = get_parts($newtext, $dot);
    // ---
    foreach ($parts as $pair) {
        list($part, $charter) = $pair;
        // ---
        echo_debug("charter=$charter\n");
        // ---
        $regline = '/((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)/us';
        // ---
        preg_match_all($regline, $part, $last_ref_matches);
        $last_ref = $last_ref_matches[1];
        // ---
        echo_debug("count(last_ref)=" . count($last_ref) . "\n");
        // ---
        if (!empty($last_ref)) {
            $ref_text = end($last_ref);
            $end_part = $ref_text . $charter;
            if (str_ends_with($part, $end_part)) {
                // ---
                echo_debug("endswith\n");
                // ---
                $new_part = trim(str_replace($end_part, '', $part)) . trim($ref_text) . $charter;
                $newtext = str_replace($part, $new_part, $newtext);
            }
        }
    }

    return $newtext;
}

function assertEqualCompare(string $expected, string $input, string $result)
{
    if ($result === $expected) {
        echo "result === expected";
    } elseif ($result === $input) {
        echo "result === input";
    } else {
        echo "result !== expected";
    }
}
