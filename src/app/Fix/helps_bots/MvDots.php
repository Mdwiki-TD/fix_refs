<?php

namespace App\Fix\helps_bots;

class MvDots
{
    public static function move_dots_before_refs(string $text, string $lang): string
    {
        $punctuation = '\.,،';
        $pattern = '/((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)([' . $punctuation . ']+)/su';

        $result = preg_replace_callback($pattern, function ($matches) {
            $punctuation = $matches[2];
            if (substr_count($punctuation, '.') > 1) {
                $punctuation = '.';
            }
            return $punctuation . ' ' . trim($matches[1]);
        }, $text);

        return $result;
    }

    public static function move_dots_after_refs($newtext, $lang)
    {
        $dot = "\.,。।";
        if ($lang === "hy") {
            $dot = "\.,。։।:";
        }
        $regline = "((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)";
        $pattern = "/([" . $dot . "]+)\s*" . $regline . "/mu";
        $replacement = "$2$1";
        $newtext = preg_replace($pattern, $replacement, $newtext);
        return $newtext;
    }
}
