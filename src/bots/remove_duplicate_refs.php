<?php

namespace WpRefs\DelDuplicateRefs;

/*

Usage:

use function WpRefs\DelDuplicateRefs\remove_Duplicate_refs;
use function WpRefs\DelDuplicateRefs\fix_refs_names;

*/

use function WpRefs\Bots\AttrsUtils\get_attrs;
use function WpRefs\Bots\RefsUtils\remove_start_end_quotes;
use function WikiParse\Citations\getCitations;
use function WpRefs\TestBot\echo_debug;

function remove_Duplicate_refs(string $text): string
{
    // ---
    $new_text = $text;
    // ---
    $refs_to_check = [];
    // ---
    $refs = [];
    // ---
    $citations = getCitations($new_text);
    // ---
    $numb = 0;
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $cite_text = $citation->getOriginalText();
        // ---
        echo_debug("\ncite_text: (($cite_text))\n");
        // ---
        // $cite_contents = $citation->getContent();
        // ---
        $cite_attrs = $citation->getAttributes();
        $cite_attrs = $cite_attrs ? trim($cite_attrs) : "";
        // ---
        if (empty($cite_attrs)) {
            $numb += 1;
            $name = "autogen_" . $numb;
            $cite_attrs = "name='$name'";
        }
        // ---
        $cite_newtext = "<ref $cite_attrs />";
        // ---
        if (isset($refs[$cite_attrs])) {
            // ---
            $new_text = str_replace($cite_text, $cite_newtext, $new_text);
        } else {
            $refs_to_check[$cite_newtext] = $cite_text;
            // ---
            $refs[$cite_attrs] = $cite_newtext;
        };
    }
    // ---
    foreach ($refs_to_check as $key => $value) {
        if (strpos($new_text, $value) === false) {
            $pattern = '/' . preg_quote($key, '/') . '/';
            $new_text = preg_replace($pattern, $value, $new_text, 1);
        }
    }
    // ---
    // echo count($citations);
    // ---
    return $new_text;
}

function fix_refs_names(string $text): string
{
    // ---
    $new_text = $text;
    // ---
    $citations = getCitations($text);
    // ---
    $new_text = $text;
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $cite_attrs = $citation->getAttributes();
        $cite_attrs = $cite_attrs ? trim($cite_attrs) : "";
        // ---
        $if_in = "<ref $cite_attrs>";
        // ---
        if (strpos($new_text, $if_in) === false) {
            continue;
        }
        // ---
        $attrs = get_attrs($cite_attrs);
        // ---
        if (empty($cite_attrs)) {
            continue;
        }
        // ---
        $new_cite_attrs = "";
        // ---
        foreach ($attrs as $key => $value) {
            // ---
            $value2 = remove_start_end_quotes($value);
            // ---
            $new_cite_attrs .= " $key=$value2";
            // ---
        }
        // ---
        $new_cite_attrs = trim($new_cite_attrs);
        // ---
        $cite_newtext = "<ref $new_cite_attrs>";
        // ---
        $new_text = str_replace($if_in, $cite_newtext, $new_text);
    }
    // ---
    return $new_text;
}
