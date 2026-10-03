<?php

namespace App\Fix\HelpsBots;

use App\Fix\DebugHelper;
use App\Fix\Parse\Citations;
use App\Fix\WikiParse\Template;

class EnLangParam
{
    public static function add_lang_en($text)
    {
        $REFS = "/(?is)(?P<pap><ref[^>\/]*>)(?P<ref>.*?<\/ref>)/";
        if (preg_match_all($REFS, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $pap = $match['pap'];
                $ref = $match['ref'];
                if (!trim($ref)) {
                    continue;
                }
                if (preg_replace("/\|\s*language\s*\=\s*\w+/u", "", $ref) != $ref) {
                    continue;
                }
                $ref2 = preg_replace("/(\|\s*language\s*\=\s*)(\|\}\})/u", "$1en$2", $ref);
                if ($ref2 == $ref) {
                    $ref2 = str_replace("}}</ref>", "|language=en}}</ref>", $ref);
                }
                if ($ref2 != $ref) {
                    $text = str_replace($pap . $ref, $pap . $ref2, $text);
                }
            }
        }
        return $text;
    }

    public static function add_lang_en_new($tempText)
    {
        $newText = $tempText;
        $tempText = trim($tempText);
        $temps = Template::getTemplates($tempText);
        foreach ($temps as $temp) {
            $tempOld = $temp->getOriginalText();
            $params = $temp->parameters;
            $language = $params->get("language", "");
            if ($language == "") {
                $params->set("language", "en");
                $tempNew = $temp->toString();
                $newText = str_replace($tempOld, $tempNew, $newText);
            }
        }
        return $newText;
    }

    public static function add_lang_en_to_refs($text)
    {
        DebugHelper::echo_debug("\n add_lang_en_to_refs:\n");
        $newText = $text;
        $citations = Citations::getCitationsOld($text);
        foreach ($citations as $key => $citation) {
            $citeTemp = $citation->getContent();
            $newTemp = self::add_lang_en_new($citeTemp);
            $newText = str_replace($citeTemp, $newTemp, $newText);
        }
        return $newText;
    }
}
