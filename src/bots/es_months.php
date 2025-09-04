<?php

namespace WpRefs\Bots\es_months;
/*
(January|February|March|April|May|June|July|August|September|October|November|December)
usage:

use function WpRefs\Bots\es_months\make_date_new_val_es;
use function WpRefs\Bots\es_months\fix_es_months;
*/

use function WpRefs\Parse\Citations\getCitationsOld;
use function WikiParse\Template\getTemplate;
use function WikiParse\Template\getTemplates;
use function WpRefs\TestBot\echo_debug;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_es;

function fix_one_cite_temp($temp_text)
{
    // ---
    $temp_text = trim($temp_text);
    // ---
    $temp = getTemplate($temp_text);
    // ---
    $params = $temp->getParameters();
    // ---
    foreach ($params as $key => $value) {
        // ---
        $new_value = make_date_new_val_es($value);
        // ---
        if ($new_value && $new_value != $value) {
            $temp->setParameter($key, $new_value);
        }
    }
    // ---
    $new_text = $temp->toString();
    // ---
    return $new_text;
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
            if ($new_value && $new_value != $value) {
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
function start_end($cite_temp)
{
    return strpos($cite_temp, "{{") === 0 && strrpos($cite_temp, "}}") === strlen($cite_temp) - 2;
}

function fix_es_months($text)
{
    // ---
    echo_debug("\nfix_es_months:\n");
    // ---
    $new_text = $text;
    // ---
    $citations = getCitationsOld($text);
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $cite_temp = $citation->getContent();
        // ---
        // echo_debug("\ncite_temp: $cite_temp\n");
        // ---
        // if $cite_temp startwith {{ and ends with }}
        // if (start_end($cite_temp) || defined("DEBUG") || True) {
        // ---
        // $new_temp = fix_one_cite_temp($cite_temp);
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
