<?php

namespace App\DelDuplicateRefs;



use function App\Bots\AttrsUtils\get_attrs;
use function App\Bots\RefsUtils\remove_start_end_quotes;
use function App\Parse\Citations\getCitationsOld;
use App\Logger;

function fix_refs_names(string $text): string
{
    // ---
    $newText = $text;
    // ---
    $citations = getCitationsOld($text);
    // ---
    $newText = $text;
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $citeAttrs = $citation->getAttributes();
        $citeAttrs = $citeAttrs ? trim($citeAttrs) : "";
        // ---
        $ifIn = "<ref $citeAttrs>";
        // ---
        if (strpos($newText, $ifIn) === false) {
            continue;
        }
        // ---
        $attrs = get_attrs($citeAttrs);
        // ---
        if (empty($citeAttrs)) {
            continue;
        }
        // ---
        $newCiteAttrs = "";
        // ---
        foreach ($attrs as $key => $value) {
            // ---
            $value2 = remove_start_end_quotes($value);
            // ---
            $newCiteAttrs .= " $key=$value2";
            // ---
        }
        // ---
        $newCiteAttrs = trim($newCiteAttrs);
        // ---
        $citeNewtext = "<ref $newCiteAttrs>";
        // ---
        $newText = str_replace($ifIn, $citeNewtext, $newText);
    }
    // ---
    return $newText;
}

function remove_Duplicate_refs_With_attrs(string $text): string
{
    // ---
    $newText = $text;
    // ---
    $refsToCheck = [];
    // ---
    $refs = [];
    // ---
    $citations = getCitationsOld($newText);
    // ---
    $numb = 0;
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $citeFulltext = $citation->getOriginalText();
        // ---
        $citeAttrs = $citation->getAttributes();
        $citeAttrs = $citeAttrs ? trim($citeAttrs) : "";
        // ---
        if (empty($citeAttrs)) {
            $numb += 1;
            $name = "autogen_" . $numb;
            $citeAttrs = "name='$name'";
        }
        // ---
        // Logger::debug("\n cite_text: (($citeFulltext))");
        Logger::debug("\n cite_attrs: (($citeAttrs))");
        // ---
        $citeNewtext = "<ref $citeAttrs />";
        // ---
        if (isset($refs[$citeAttrs])) {
            // ---
            $newText = str_replace($citeFulltext, $citeNewtext, $newText);
        } else {
            $refsToCheck[$citeNewtext] = $citeFulltext;
            // ---
            $refs[$citeAttrs] = $citeNewtext;
        };
    }
    // ---
    foreach ($refsToCheck as $key => $value) {
        if (strpos($newText, $value) === false) {
            $pattern = '/' . preg_quote($key, '/') . '/u';
            $newText = preg_replace($pattern, $value, $newText, 1);
        }
    }
    // ---
    // echo count($citations);
    // ---
    return $newText;
}
