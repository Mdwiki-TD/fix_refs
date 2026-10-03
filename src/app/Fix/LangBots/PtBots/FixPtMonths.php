<?php

namespace WpRefs\PT\FixPtMonth;


use function WpRefs\TestBot\echo_test;
use function WpRefs\TestBot\echo_debug;
use function WpRefs\Parse\Citations\getCitationsOld;
use function WpRefs\WikiParse\getTemplates;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_pt;


function start_end($citeTemp)
{
    return strpos($citeTemp, "{{") === 0 && strrpos($citeTemp, "}}") === strlen($citeTemp) - 2;
}

function rm_ref_spaces($newtext)
{
    // ---
    // \s*(\.|,|。|।)\s*((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)
    // ---
    $dot = "(\.|,|。|।)";
    // ---
    $regline = "((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)";
    // ---
    $pattern = "/\s*" . $dot . "\s*" . $regline . "/m";
    $replacement = "$1$2";
    // ---
    $newtext = preg_replace($pattern, $replacement, $newtext);
    // ---
    return $newtext;
}

function fix_pt_months_in_texts($tempText)
{
    // ---
    $newText = $tempText;
    // ---
    $tempText = trim($tempText);
    // ---
    $temps = getTemplates($tempText);
    // ---
    foreach ($temps as $temp) {
        // ---
        $tempOld = $temp->getOriginalText();
        // ---
        // echo_debug("temp_old:($tempOld)\n");
        // ---
        $params = $temp->getParameters();
        // ---
        foreach ($params as $key => $value) {
            // ---
            $newValue = make_date_new_val_pt($value);
            // ---
            // if ($newValue && $newValue != trim($value)) {
            if ($newValue !== null && trim((string)$newValue) !== trim((string)$value)) {
                $temp->setParameter($key, $newValue);
            }
        }
        // ---
        $tempNew = $temp->toString();
        // ---
        $newText = str_replace($tempOld, $tempNew, $newText);
        // ---
    }
    return $newText;
}

function fix_pt_months_in_refs($text)
{
    // ---
    echo_debug("\n fix_pt_months_in_refs:\n");
    // ---
    $newText = $text;
    // ---
    $citations = getCitationsOld($text);
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $citeTemp = $citation->getContent();
        // ---
        // echo_debug("\n cite_temp: $citeTemp\n");
        // ---
        // if $citeTemp startwith {{ and ends with }}
        // if (start_end($citeTemp) || defined("DEBUG") || True) {
        // ---
        $newTemp = fix_pt_months_in_texts($citeTemp);
        // ---
        // if ($newTemp != $citeTemp) echo_debug("new_temp != cite_temp\n");
        // ---
        $newText = str_replace($citeTemp, $newTemp, $newText);
        // }
    }
    // ---
    return $newText;
}

function pt_fixes($text)
{
    // ---
    $text = fix_pt_months_in_refs($text);
    // ---
    $text = rm_ref_spaces($text);
    // ---
    return $text;
}
