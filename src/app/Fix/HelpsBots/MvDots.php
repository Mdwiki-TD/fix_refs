<?php

namespace App\Fix\HelpsBots;

class MvDots
{
    public static function move_dots_before_refs(string $text, string $lang): string
    {
        // Define punctuation marks based on language
        $punctuation = '\.,،';

        $pattern = '/((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)([' . $punctuation . ']+)/su';

        // Replace by moving punctuation before the reference(s)
        $result = (string)preg_replace_callback($pattern, function (array $matches): string {
            // Handle multiple dots by replacing with a single dot
            $punctuationMark = $matches[2];
            if (substr_count($punctuationMark, '.') > 1) {
                $punctuationMark = '.';
            }
            return $punctuationMark . ' ' . trim($matches[1]);
        }, $text);

        return $result;
    }

    public static function move_dots_after_refs(string $newtext, string $lang): string
    {
        $dot = "\.,。।";

        if ($lang === "hy") {
            $dot = "\.,。։।:";
        }

        $regline = "((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)";

        $pattern = "/([" . $dot . "]+)\s*" . $regline . "/mu";
        $replacement = "$2$1";

        $newtext = (string)preg_replace($pattern, $replacement, $newtext);

        return $newtext;
    }
}
