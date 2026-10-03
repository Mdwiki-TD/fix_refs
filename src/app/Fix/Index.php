<?php

namespace App\Fix;

use App\Logger;
use App\Fix\Bots\MiniFixesBot;
use App\Fix\Bots\RedirectHelp;
use App\Fix\Bots\RemoveDuplicateRefs;
use App\Fix\HelpsBots\EnLangParam;
use App\Fix\HelpsBots\MissingRefs;
use App\Fix\HelpsBots\MvDots;
use App\Fix\HelpsBots\RemoveSpace;
use App\Fix\Infoboxes\Infobox;
use App\Fix\LangBots\BgBots\FixBg;
use App\Fix\LangBots\EsBots\Es;
use App\Fix\LangBots\EsBots\Section;
use App\Fix\LangBots\PlBots\FixPlInfobox;
use App\Fix\LangBots\PtBots\FixPtMonths;
use App\Fix\LangBots\SwBot;

class Index
{
    public static function fix_page($text, $title, $moveDots, $infobox, $addEnLang, $lang, $sourcetitle, $mdwikiRevid)
    {
        $textOrg = $text;

        if (RedirectHelp::page_is_redirect($title, $text)) {
            return $text;
        }

        if ($lang === "pl") {
            $text = FixPlInfobox::pl_fixes($text);
        }

        if ($infobox || $lang === "es") {
            Logger::debug("Expend_Infobox\n");
            $text = Infobox::Expend_Infobox($text, $title, "");
        }

        $text = MiniFixesBot::mini_fixes($text, $lang);
        $text = MissingRefs::fix_missing_refs($text, $sourcetitle, $mdwikiRevid);
        $text = RemoveDuplicateRefs::remove_Duplicate_refs_With_attrs($text);

        if ($moveDots) {
            Logger::debug("move_dots\n");
            $text = MvDots::move_dots_after_refs($text, $lang);
        }

        if ($addEnLang) {
            Logger::debug("add_en_lang\n");
            $text = EnLangParam::add_lang_en_to_refs($text);
        }

        if ($lang === "pt") {
            $text = FixPtMonths::pt_fixes($text);
        }

        if ($lang === "bg") {
            $text = FixBg::bg_fixes($text, $sourcetitle, $mdwikiRevid);
        }

        if ($lang === "es") {
            $text = Es::fix_es($text, $title);
            $text = Section::es_section($sourcetitle, $text, $mdwikiRevid);
        }

        if ($lang == 'sw') {
            $text = SwBot::sw_fixes($text);
        }

        if ($lang === "hy") {
            $text = RemoveSpace::remove_spaces_between_last_word_and_beginning_of_ref($text, "hy");
            $text = RemoveSpace::remove_spaces_between_ref_and_punctuation($text);
        }

        if ($lang !== "bg") {
            $text = MdCat::add_Translated_from_MDWiki($text, $lang);
        }

        $text = MiniFixesBot::mini_fixes_after_fixing($text, $lang);

        if (!empty($text)) {
            return $text;
        }

        return $textOrg;
    }
}
