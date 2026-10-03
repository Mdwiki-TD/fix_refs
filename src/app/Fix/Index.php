<?php

namespace App\Fix;

use App\Logger;
use function App\Fix\Bots\MiniFixesBot\mini_fixes;
use function App\Fix\Bots\MiniFixesBot\mini_fixes_after_fixing;
use function App\Fix\Bots\RedirectHelp\page_is_redirect;
use function App\Fix\Bots\RemoveDuplicateRefs\remove_Duplicate_refs_With_attrs;
use function App\Fix\HelpsBots\EnLangParam\add_lang_en_to_refs;
use function App\Fix\HelpsBots\MissingRefs\fix_missing_refs;
use function App\Fix\HelpsBots\MvDots\move_dots_after_refs;
use function App\Fix\HelpsBots\RemoveSpace\remove_spaces_between_last_word_and_beginning_of_ref;
use function App\Fix\HelpsBots\RemoveSpace\remove_spaces_between_ref_and_punctuation;
use function App\Fix\Infoboxes\Infobox\Expend_Infobox;
use function App\Fix\LangBots\BgBots\FixBg\bg_fixes;
use function App\Fix\LangBots\EsBots\ES\fix_es;
use function App\Fix\LangBots\EsBots\Section\es_section;
use function App\Fix\LangBots\PlBots\FixPlInfobox\pl_fixes;
use function App\Fix\LangBots\PtBots\FixPtMonths\pt_fixes;
use function App\Fix\LangBots\SwBot\sw_fixes;
use function App\Fix\MdCat\add_Translated_from_MDWiki;

class Index
{
    public static function fix_page($text, $title, $moveDots, $infobox, $addEnLang, $lang, $sourcetitle, $mdwikiRevid)
    {
        $textOrg = $text;

        if (page_is_redirect($title, $text)) {
            return $text;
        }

        if ($lang === "pl") {
            $text = pl_fixes($text);
        }

        // print_s("fix page: $title, move_dots:$moveDots, expend_infobox:$infobox");

        if ($infobox || $lang === "es") {
            Logger::debug("Expend_Infobox\n");
            $text = Expend_Infobox($text, $title, "");
        }

        // $text = remove_False_code($text);

        // $text = fix_refs_names($text);

        $text = mini_fixes($text, $lang);

        $text = fix_missing_refs($text, $sourcetitle, $mdwikiRevid);

        $text = remove_Duplicate_refs_With_attrs($text);

        if ($moveDots) {
            Logger::debug("move_dots\n");
            $text = move_dots_after_refs($text, $lang);
        }

        if ($addEnLang) {
            Logger::debug("add_en_lang\n");
            $text = add_lang_en_to_refs($text);
        }

        if ($lang === "pt") {
            $text = pt_fixes($text);
        }

        if ($lang === "bg") {
            $text = bg_fixes($text, $sourcetitle, $mdwikiRevid);
        }

        if ($lang === "es") {
            $text = fix_es($text, $title);
            $text = es_section($sourcetitle, $text, $mdwikiRevid);
        }

        if ($lang == 'sw') {
            $text = sw_fixes($text);
        };

        if ($lang === "hy") {
            $text = remove_spaces_between_last_word_and_beginning_of_ref($text, "hy");
            $text = remove_spaces_between_ref_and_punctuation($text);
        }

        if ($lang !== "bg") {
            $text = add_Translated_from_MDWiki($text, $lang);
        }

        $text = mini_fixes_after_fixing($text, $lang);

        if (!empty($text)) {
            return $text;
        }

        return $textOrg;
    }
}
