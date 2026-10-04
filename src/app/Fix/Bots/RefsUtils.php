<?php

namespace App\Fix\Bots;

class RefsUtils
{
    public static function rm_str_from_start_and_end(string $text, string $find): string
    {
        if (!$find) {
            return $text;
        }

        $text = trim($text);

        if (str_starts_with($text, $find) && str_ends_with($text, $find)) {
            $text = substr($text, strlen($find), -strlen($find));
        }

        return trim($text);
    }

    public static function remove_start_end_quotes(string $text): string
    {
        $text = trim($text);

        $text = self::rm_str_from_start_and_end($text, '"');
        $text = self::rm_str_from_start_and_end($text, "'");

        $quote = strpos($text, '"') === false ? '"' : "'";

        return $quote . $text . $quote;
    }
}
