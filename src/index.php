<?php

namespace WpRefs\WprefText;

/*
usage:

use function WpRefs\WprefText\fix_page;

*/

use function WpRefs\TestBot\echo_test;
use function WpRefs\Infobox\Expend_Infobox;
use function WpRefs\FixPtMonth\pt_fixes;
use function WpRefs\SW\sw_fixes;
use function WpRefs\ES\fix_es;
use function WpRefs\ES\es_section;
// use function WpRefs\DelDuplicateRefs\fix_refs_names;
use function WpRefs\DelDuplicateRefs\remove_Duplicate_refs;
use function WpRefs\MoveDots\move_dots_text;
use function WpRefs\MoveDots\add_lang_en;
use function WpRefs\MdCat\add_Translated_from_MDWiki;
use function WpRefs\Bots\Mini\mini_fixes;
use function WpRefs\Bots\Mini\mini_fixes_after_fixing;


function fix_page($text, $title, $move_dots, $infobox, $add_en_lang, $lang, $sourcetitle, $mdwiki_revid)
{
    // ---
    $text_org = $text;
    // ---
    // print_s("fix page: $title, move_dots:$move_dots, expend_infobox:$infobox");
    // ---
    if ($infobox || $lang === "es") {
        echo_test("Expend_Infobox\n");
        $text = Expend_Infobox($text, $title, "");
    }
    // ---
    // $text = remove_False_code($text);
    // ---
    // $text = fix_refs_names($text);
    // ---
    $text = mini_fixes($text, $lang);
    // ---
    $text = remove_Duplicate_refs($text);
    // ---
    if ($move_dots) {
        echo_test("move_dots\n");
        $text = move_dots_text($text, $lang);
    }
    // ---
    if ($add_en_lang) {
        echo_test("add_en_lang\n");
        $text = add_lang_en($text);
    }
    // ---
    if ($lang === "pt") {
        $text = pt_fixes($text);
    }
    // ---
    if ($lang === "es") {
        $text = fix_es($text, $title);
        $text = es_section($sourcetitle, $text, $mdwiki_revid);
    }
    // ---
    if ($lang == 'sw') {
        $text = sw_fixes($text);
    };
    // ---
    $text = add_Translated_from_MDWiki($text, $lang);
    // ---
    $text = mini_fixes_after_fixing($text, $lang);
    // ---
    if (!empty($text)) {
        return $text;
    }
    // ---
    return $text_org;
}
