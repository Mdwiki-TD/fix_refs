<?php

namespace App\Fix\Bots\RefsUtils;

function rm_str_from_start_and_end(string $text, string $find): string
{

    if (!$find) {
        return $text;
    }

    $text = trim($text);

    if (str_starts_with($text, $find) && str_ends_with($text, $find)) {

        // $text = substr($text, strlen($find)); // إزالة $find من البداية
        // $text = substr($text, 0, -strlen($find)); // إزالة $find من النهاية

        $text = substr($text, strlen($find), -strlen($find));
    }

    return trim($text);
}

function remove_start_end_quotes(string $text): string
{

    $text = trim($text);

    $text = rm_str_from_start_and_end($text, '"');
    $text = rm_str_from_start_and_end($text, "'");

    // Logger::debug("\n$text\n");

    $quote = strpos($text, '"') === false ? '"' : "'";

    return $quote . $text . $quote;
}
