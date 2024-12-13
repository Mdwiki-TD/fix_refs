<?php

namespace WpRefs\WprefText;

/*
usage:

use function WpRefs\WprefText\fix_page;

*/

use function WpRefs\Infobox\Expend_Infobox;
use function WpRefs\FixPtMonth\pt_months;
use function WpRefs\ES\fix_es;
use function WpRefs\DelDuplicateRefs\remove_Duplicate_refs;
use function WpRefs\MoveDots\move_dots_text;
use function WpRefs\MoveDots\add_lang_en;

function fix_page($text, $title, $move_dots, $infobox, $add_en_lang, $lang)
{
    // ---
    // print_s("fix page: $title, move_dots:$move_dots, expend_infobox:$infobox");
    // ---
    if ($infobox) {
        echo "Expend_Infobox\n";
        $text = Expend_Infobox($text, $title, "");
    }
    // ---
    // $text = remove_False_code($text);
    // ---
    $text = remove_Duplicate_refs($text);
    // ---
    if ($move_dots) {
        echo "move_dots\n";
        $text = move_dots_text($text, $lang);
    }
    // ---
    if ($add_en_lang) {
        echo "add_en_lang\n";
        $text = add_lang_en($text);
    }
    // ---
    if ($lang === "pt") {
        $text = pt_months($text);
    }
    // ---
    if ($lang === "es") {
        $text = fix_es($text, $title);
    }
    // ---
    return $text;
}
