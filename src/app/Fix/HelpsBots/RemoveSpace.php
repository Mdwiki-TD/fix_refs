<?php

namespace App\Fix\HelpsBots;

use App\Logger;

class RemoveSpace
{
    public static function match_it(string $text, string $charters): ?string
    {
        $pattern = '/(<\/ref>|\/>)\s*([' . preg_quote($charters, '/') . ']\s*)$/u';
        if (preg_match($pattern, $text, $m)) {
            return $m[2];
        }
        return null;
    }

    /**
     * @param string $newtext
     * @param string $charters
     * @return array<int, array{0: string, 1: string}>
     */
    public static function get_parts(string $newtext, string $charters): array
    {
        $matches = explode("\n\n", $newtext);

        if (count($matches) == 1) {
            $matches = explode("\r\n\r\n", $newtext);
        }

        Logger::debug("count(matches)=" . count($matches) . "\n");

        $newParts = [];

        foreach ($matches as $p) {
            $chart = self::match_it($p, $charters);
            if ($chart) {
                $newParts[] = [$p, $chart];
            }
        }

        Logger::debug("count(new_parts)=" . count($newParts) . "\n");

        return $newParts;
    }

    public static function remove_spaces_between_last_word_and_beginning_of_ref(string $newtext, string $lang): string
    {
        $dots = ".,。।";

        if ($lang === "hy") {
            $dots = ".,。।։:";
        }
        $newtext = (string)preg_replace('/>\s*<ref/', '><ref', $newtext);
        $parts = self::get_parts($newtext, $dots);

        foreach ($parts as $pair) {
            list($part, $charter) = $pair;

            Logger::debug("charter=$charter\n");

            $regline = '/((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)/us';

            preg_match_all($regline, $part, $lastRefMatches);
            $lastRef = $lastRefMatches[1];

            Logger::debug("count(last_ref)=" . count($lastRef) . "\n");

            if (!empty($lastRef)) {
                $refText = end($lastRef);
                $endPart = $refText . $charter;
                if (str_ends_with($part, $endPart)) {

                    Logger::debug("endswith\n");

                    $firstPartCleanEnd = substr($part, 0, -strlen($endPart));
                    $firstPartCleanEnd = rtrim($firstPartCleanEnd);

                    $newPart = $firstPartCleanEnd . trim($refText) . $charter;

                    $newtext = str_replace($part, $newPart, $newtext);
                }
            }
        }

        return $newtext;
    }

    public static function remove_spaces_between_ref_and_punctuation(string $text, ?string $lang = null): string
    {
        // Use a superset of punctuation across supported languages
        $dots = ".,。։।:";
        $cls = preg_quote($dots, '/');

        // Keep punctuation right after <ref ... /> with no space
        $text = (string)preg_replace('/(<ref[^>]*\/>)\s*([' . $cls . '])/u', '$1$2', $text);
        // Normalize endings: </ref> followed by any punctuation remains attached
        $text = (string)preg_replace('/<\/ref>\s*([' . $cls . '])/u', '</ref>$1', $text);

        return $text;
    }
}
