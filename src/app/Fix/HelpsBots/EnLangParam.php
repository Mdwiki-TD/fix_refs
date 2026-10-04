<?php

namespace App\Fix\HelpsBots;

use App\Logger;
use App\Fix\Parse\Citations;
use App\Fix\WikiParse\ParserTemplates;

class EnLangParam
{
    public static function add_lang_en(string $text): string
    {
        // Match references
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
                $ref2 = (string)preg_replace("/(\|\s*language\s*\=\s*)(\|\}\})/u", "$1en$2", $ref);
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

    public static function add_lang_en_new(string $tempText): string
    {
        $newText = $tempText;
        $tempText = trim($tempText);
        $temps = (new ParserTemplates($tempText))->getTemplates();
        foreach ($temps as $temp) {
            $tempOld = $temp->getOriginalText();

            // Logger::debug("temp_old:($tempOld)\n");

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

    public static function addLangEnToRefs(string $text): string
    {
        Logger::debug("\n addLangEnToRefs:\n");
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
