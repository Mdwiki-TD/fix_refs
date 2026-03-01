<?php

namespace WpRefs\EsBots\es_months;
/*
usage:

use function WpRefs\EsBots\es_months\fix_es_months_in_texts;
use function WpRefs\EsBots\es_months\fix_es_months_in_refs;
*/

use function WpRefs\TestBot\echo_test;
use function WpRefs\TestBot\echo_debug;
use function WpRefs\Parse\Citations\getCitationsOld;
use function WikiParse\Template\getTemplates;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_es;


function start_end($cite_temp)
{
    return strpos($cite_temp, "{{") === 0 && strrpos($cite_temp, "}}") === strlen($cite_temp) - 2;
}

function fix_es_months_in_texts($temp_text)
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
        $params = $temp->getParameters();
        // ---
        foreach ($params as $key => $value) {
            // ---
            $new_value = make_date_new_val_es($value);
            // ---
            // if ($new_value && $new_value != trim($value)) {
            if ($new_value !== null && trim((string)$new_value) !== trim((string)$value)) {
                $temp->setParameter($key, $new_value);
            }
        }
        // ---
        $temp_new = $temp->toString();
        // ---
        $new_text = str_replace($temp_old, $temp_new, $new_text);
        // ---
    }
    return $new_text;
}

function fix_es_months_in_refs($text)
{
    // ---
    echo_debug("\n fix_es_months_in_refs:\n");
    // ---
    $new_text = $text;
    // ---
    $citations = getCitationsOld($text);
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $cite_temp = $citation->getContent();
        // ---
        // echo_debug("\n cite_temp: $cite_temp\n");
        // ---
        // if $cite_temp startwith {{ and ends with }}
        // if (start_end($cite_temp) || defined("DEBUG") || True) {
        // ---
        $new_temp = fix_es_months_in_texts($cite_temp);
        // ---
        // if ($new_temp != $cite_temp) echo_debug("new_temp != cite_temp\n");
        // ---
        $new_text = str_replace($cite_temp, $new_temp, $new_text);
        // }
    }
    // ---
    return $new_text;
}
