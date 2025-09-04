<?php

namespace WpRefs\Bots\RefsUtils;

/*

Usage:

use function WpRefs\Bots\RefsUtils\endsWith;
use function WpRefs\Bots\RefsUtils\strstartswith;
use function WpRefs\Bots\RefsUtils\del_start_end;
use function WpRefs\Bots\RefsUtils\remove_start_end_quotes;

*/

function endsWith($string, $endString)
{
    $len = strlen($endString);
    return substr($string, -$len) === $endString;
}

function strstartswith($text, $start)
{
    return strpos($text, $start) === 0;
}

function del_start_end(string $text, string $find): string
{
    // ---
    $text = trim($text);
    // ---
    // if (str_starts_with($text, $find) && str_ends_with($text, $find)) {
    if (strstartswith($text, $find) && endsWith($text, $find)) {
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
    $text = del_start_end($text, '"');
    $text = del_start_end($text, "'");
    // ---
    // echo_test("\n$text\n");
    // ---
    $quote = strpos($text, '"') === false ? '"' : "'";
    // ---
    return $quote . $text . $quote;
}
