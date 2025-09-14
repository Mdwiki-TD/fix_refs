<?php

namespace WpRefs\EnLangParam;

/*
usage:

use function WpRefs\EnLangParam\add_lang_en;
use function WpRefs\EnLangParam\add_lang_en_to_refs;

*/

// use function WpRefs\TestBot\echo_test;
use function WpRefs\TestBot\echo_debug;
use function WpRefs\Parse\Citations\getCitationsOld;
use function WikiParse\Template\getTemplates;

function add_lang_en($text)
{
    // ---
    // Match references
    $REFS = "/(?is)(?P<pap><ref[^>\/]*>)(?P<ref>.*?<\/ref>)/";
    // ---
    if (preg_match_all($REFS, $text, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $pap = $match['pap'];
            $ref = $match['ref'];
            // ---
            if (!trim($ref)) {
                continue;
            }
            // ---
            if (preg_replace("/\|\s*language\s*\=\s*\w+/", "", $ref) != $ref) {
                continue;
            }
            // ---
            $ref2 = preg_replace("/(\|\s*language\s*\=\s*)(\|\}\})/", "$1en$2", $ref);
            // ---
            if ($ref2 == $ref) {
                $ref2 = str_replace("}}</ref>", "|language=en}}</ref>", $ref);
            }
            // ---
            if ($ref2 != $ref) {
                $text = str_replace($pap . $ref, $pap . $ref2, $text);
            }
        }
    }
    // ---
    return $text;
}

function add_lang_en_new($temp_text)
{
    // ---
    $new_text = $temp_text;
    // ---
    $temp_text = trim($temp_text);
    // ---
    $temps = getTemplates($temp_text);
    // ---
    foreach ($temps as $temp) {
        // ---
        $temp_old = $temp->getOriginalText();
        // ---
        // echo_debug("temp_old:($temp_old)\n");
        // ---
        $params = $temp->parameters;
        // ---
        $language = $params->get("language", "");
        // ---
        if ($language == "") {
            // ---
            $params->set("language", "en");
            // ---
            $temp_new = $temp->toString();
            // ---
            $new_text = str_replace($temp_old, $temp_new, $new_text);
        }
    }
    // ---
    return $new_text;
}

function add_lang_en_to_refs($text)
{
    // ---
    echo_debug("\n add_lang_en_to_refs:\n");
    // ---
    $new_text = $text;
    // ---
    $citations = getCitationsOld($text);
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $cite_temp = $citation->getContent();
        // ---
        $new_temp = add_lang_en_new($cite_temp);
        // ---
        $new_text = str_replace($cite_temp, $new_temp, $new_text);
    }
    // ---
    return $new_text;
}
