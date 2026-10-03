<?php

namespace App\fix_src\helps_bots;

use App\fix_src\DebugHelper;

class RemoveSpace
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

    public static function match_it($text, $charters)
    {
        $pattern = '/(<\/ref>|\/>)\s*([' . preg_quote($charters, '/') . ']\s*)$/u';
        if (preg_match($pattern, $text, $m)) {
            return $m[2];
        }
        return null;
    }

    public static function get_parts($newtext, $charters)
    {
        $matches = explode("\n\n", $newtext);
        if (count($matches) == 1) {
            $matches = explode("\r\n\r\n", $newtext);
        }
        DebugHelper::echo_debug("count(matches)=" . count($matches) . "\n");
        $newParts = [];
        foreach ($matches as $p) {
            $chart = self::match_it($p, $charters);
            if ($chart) {
                $newParts[] = [$p, $chart];
            }
        }
        DebugHelper::echo_debug("count(new_parts)=" . count($newParts) . "\n");
        return $newParts;
    }

    public static function remove_spaces_between_last_word_and_beginning_of_ref($newtext, $lang)
    {
        $dots = ".,。।";
        if ($lang === "hy") {
            $dots = ".,。।։:";
        }
        $newtext = preg_replace('/>\s*<ref/', '><ref', $newtext);
        $parts = self::get_parts($newtext, $dots);
        foreach ($parts as $pair) {
            list($part, $charter) = $pair;
            DebugHelper::echo_debug("charter=$charter\n");
            $regline = '/((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)/us';
            preg_match_all($regline, $part, $lastRefMatches);
            $lastRef = $lastRefMatches[1];
            DebugHelper::echo_debug("count(last_ref)=" . count($lastRef) . "\n");
            if (!empty($lastRef)) {
                $refText = end($lastRef);
                $endPart = $refText . $charter;
                if (self::str_ends_with($part, $endPart)) {
                    DebugHelper::echo_debug("endswith\n");
                    $firstPartCleanEnd = substr($part, 0, -strlen($endPart));
                    $firstPartCleanEnd = rtrim($firstPartCleanEnd);
                    $newPart = $firstPartCleanEnd . trim($refText) . $charter;
                    $newtext = str_replace($part, $newPart, $newtext);
                }
            }
        }
        return $newtext;
    }

    public static function remove_spaces_between_ref_and_punctuation($text, $lang = null)
    {
        $dots = ".,。։।:";
        $cls = preg_quote($dots, '/');
        $text = preg_replace('/(<ref[^>]*\/>)\s*([' . $cls . '])/u', '$1$2', $text);
        $text = preg_replace('/<\/ref>\s*([' . $cls . '])/u', '</ref>$1', $text);
        return $text;
    }
}
