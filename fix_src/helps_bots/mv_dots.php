<?php

namespace WpRefs\MovesDots;

/*
usage:

use function WpRefs\MovesDots\move_dots_after_refs;

*/

function move_dots_before_refs(string $text, string $lang): string
{
    // Define punctuation marks based on language
    $punctuation = '\.,،';

    // Pattern to match references followed by punctuation
    // This pattern handles one or more ref tags followed by punctuation
    $pattern = '/((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)([\.,،])/su';
    $pattern = '/((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)([' . $punctuation . ']+)/su';

    // Replace by moving punctuation before the reference(s)
    $result = preg_replace_callback($pattern, function ($matches) {
        // Handle multiple dots by replacing with a single dot
        $punctuation = $matches[2];
        if (substr_count($punctuation, '.') > 1) {
            $punctuation = '.';
        }
        return $punctuation . ' ' . trim($matches[1]);
    }, $text);

    return $result;
}

function move_dots_after_refs($newtext, $lang)
{
    // ---
    // echo_test("move_dots_after_refs\n");
    // ---
    $dot = "\.,。।";
    // ---
    if ($lang === "hy") {
        $dot = "\.,。։।:";
    }
    // ---
    $regline = "((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)";
    // ---
    $pattern = "/([" . $dot . "]+)\s*" . $regline . "/mu";
    $replacement = "$2$1";
    // ---
    $newtext = preg_replace($pattern, $replacement, $newtext);
    // ---
    return $newtext;
}
