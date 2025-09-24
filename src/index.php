<?php

namespace WpRefs\WprefText;

/*
usage:

use function WpRefs\WprefText\fix_page;

*/

use function WpRefs\TestBot\echo_test;
use function WpRefs\Infobox\Expend_Infobox;
use function WpRefs\PT\FixPtMonth\pt_fixes;
use function WpRefs\BG\bg_fixes;
use function WpRefs\SW\sw_fixes;
use function WpRefs\ES\fix_es;
use function WpRefs\EsBots\Section\es_section;
// use function WpRefs\DelDuplicateRefs\fix_refs_names;
use function WpRefs\DelDuplicateRefs\remove_Duplicate_refs_With_attrs;
use function WpRefs\MovesDots\move_dots_after_refs;
use function WpRefs\EnLangParam\add_lang_en_to_refs;
use function WpRefs\MdCat\add_Translated_from_MDWiki;
use function WpRefs\Bots\Mini\mini_fixes;
use function WpRefs\Bots\Mini\mini_fixes_after_fixing;
use function WpRefs\RemoveSpace\remove_spaces_between_last_word_and_beginning_of_ref;
use function WpRefs\RemoveSpace\remove_spaces_between_ref_and_punctuation;
use function WpRefs\MissingRefs\fix_missing_refs;
use function WpRefs\Bots\Redirect\page_is_redirect;

function fix_page($text, $title, $move_dots, $infobox, $add_en_lang, $lang, $sourcetitle, $mdwiki_revid)
{
    // ---
    $text_org = $text;
    // ---
    if (page_is_redirect($title, $text)) {
        return $text;
    }
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
    $text = fix_missing_refs($text, $sourcetitle, $mdwiki_revid);
    // ---
    $text = remove_Duplicate_refs_With_attrs($text);
    // ---
    if ($move_dots) {
        echo_test("move_dots\n");
        $text = move_dots_after_refs($text, $lang);
    }
    // ---
    if ($add_en_lang) {
        echo_test("add_en_lang\n");
        $text = add_lang_en_to_refs($text);
    }
    // ---
    if ($lang === "pt") {
        $text = pt_fixes($text);
    }
    // ---
    if ($lang === "bg") {
        $text = bg_fixes($text, $sourcetitle, $mdwiki_revid);
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
    if ($lang === "hy") {
        $text = remove_spaces_between_last_word_and_beginning_of_ref($text, "hy");
        $text = remove_spaces_between_ref_and_punctuation($text);
    }
    // ---
    if ($lang !== "bg") {
        $text = add_Translated_from_MDWiki($text, $lang);
    }
    // ---
    $text = mini_fixes_after_fixing($text, $lang);
    // ---
    if (!empty($text)) {
        return $text;
    }
    // ---
    return $text_org;
}
