<?php

namespace WpRefs\Bots\RefsUtils;

/*

Usage:

use function WpRefs\Bots\RefsUtils\str_ends_with;
use function WpRefs\Bots\RefsUtils\str_starts_with;
use function WpRefs\Bots\RefsUtils\rm_str_from_start_and_end;
use function WpRefs\Bots\RefsUtils\remove_start_end_quotes;

*/

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

function rm_str_from_start_and_end(string $text, string $find): string
{
    // ---
    if (!$find) {
        return $text;
    }
    // ---
    $text = trim($text);
    // ---
    if (str_starts_with($text, $find) && str_ends_with($text, $find)) {
        // ---
        // $text = substr($text, strlen($find)); // إزالة $find من البداية
        // $text = substr($text, 0, -strlen($find)); // إزالة $find من النهاية
        // ---
        $text = substr($text, strlen($find), -strlen($find));
    }
    // ---
    return trim($text);
}

function remove_start_end_quotes(string $text): string
{
    // ---
    $text = trim($text);
    // ---
    $text = rm_str_from_start_and_end($text, '"');
    $text = rm_str_from_start_and_end($text, "'");
    // ---
    // echo_test("\n$text\n");
    // ---
    $quote = strpos($text, '"') === false ? '"' : "'";
    // ---
    return $quote . $text . $quote;
}
