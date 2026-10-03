<?php

namespace App\fix_src\bots;

class RefsUtils
{
    public static function str_ends_with($string, $endString)
    {
        if (function_exists('str_ends_with')) {
            return \str_ends_with($string, $endString);
        }
        $len = strlen($endString);
        return substr($string, -$len) === $endString;
    }

    public static function str_starts_with($text, $start)
    {
        if (function_exists('str_starts_with')) {
            return \str_starts_with($text, $start);
        }
        return strpos($text, $start) === 0;
    }

    public static function rm_str_from_start_and_end(string $text, string $find): string
    {
        if (!$find) {
            return $text;
        }
        $text = trim($text);
        if (self::str_starts_with($text, $find) && self::str_ends_with($text, $find)) {
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
