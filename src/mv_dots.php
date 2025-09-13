<?php

namespace WpRefs\MovesDots;

/*
usage:

use function WpRefs\MovesDots\move_dots_text;

*/

function move_dots_text($newtext, $lang)
{
    // ---
    // echo_test("move_dots_text\n");
    // ---
    $dot = "(\.|,|。|।)";
    // ---
    if ($lang === "hy") {
        $dot = "(\.|,|。|।|։)";
    }
    // ---
    $regline = "((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)";
    // ---
    $pattern = "/" . $dot . "\s*" . $regline . "/m";
    $replacement = "$2$1";
    // ---
    $newtext = preg_replace($pattern, $replacement, $newtext);
    // ---
    return $newtext;
}
