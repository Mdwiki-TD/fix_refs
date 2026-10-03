<?php

namespace WpRefs\WprefText;

use App\Logger;
use function WpRefs\Infobox\Expend_Infobox;
use function WpRefs\PT\FixPtMonth\pt_fixes;
use function WpRefs\PL\FixPlInfobox\pl_fixes;
use function WpRefs\BG\bg_fixes;
use function WpRefs\SW\sw_fixes;
use function WpRefs\ES\fix_es;
use function WpRefs\EsBots\Section\es_section;
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

function fix_page($text, $title, $moveDots, $infobox, $addEnLang, $lang, $sourcetitle, $mdwikiRevid)
{
    // ---
    $textOrg = $text;
    // ---
    if (page_is_redirect($title, $text)) {
        return $text;
    }
    // ---
    if ($lang === "pl") {
        $text = pl_fixes($text);
    }
    // ---
    // print_s("fix page: $title, move_dots:$moveDots, expend_infobox:$infobox");
    // ---
    if ($infobox || $lang === "es") {
        Logger::debug("Expend_Infobox\n");
        $text = Expend_Infobox($text, $title, "");
    }
    // ---
    // $text = remove_False_code($text);
    // ---
    // $text = fix_refs_names($text);
    // ---
    $text = mini_fixes($text, $lang);
    // ---
    $text = fix_missing_refs($text, $sourcetitle, $mdwikiRevid);
    // ---
    $text = remove_Duplicate_refs_With_attrs($text);
    // ---
    if ($moveDots) {
        Logger::debug("move_dots\n");
        $text = move_dots_after_refs($text, $lang);
    }
    // ---
    if ($addEnLang) {
        Logger::debug("add_en_lang\n");
        $text = add_lang_en_to_refs($text);
    }
    // ---
    if ($lang === "pt") {
        $text = pt_fixes($text);
    }
    // ---
    if ($lang === "bg") {
        $text = bg_fixes($text, $sourcetitle, $mdwikiRevid);
    }
    // ---
    if ($lang === "es") {
        $text = fix_es($text, $title);
        $text = es_section($sourcetitle, $text, $mdwikiRevid);
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
    return $textOrg;
}
