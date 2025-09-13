<?php

namespace WpRefs\MovesDots;

/*
usage:

use function WpRefs\MovesDots\move_dots_after_refs;

*/

function move_dots_before_refs($newtext, $lang) {}

function move_dots_after_refs($newtext, $lang)
{
    // ---
    // echo_test("move_dots_after_refs\n");
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
