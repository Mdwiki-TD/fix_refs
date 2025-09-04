<?php

namespace WpRefs\FixPtMonth;
/*
usage:

use function WpRefs\FixPtMonth\pt_months;

*/

// use function WikiParse\Citations\get_full_refs;
use function WikiParse\Citations\getCitations;
use function WikiParse\Template\getTemplate;
use function WikiParse\Template\getTemplates;
use function WpRefs\TestBot\echo_test;
use function WpRefs\TestBot\echo_debug;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_pt;

function fix_one_cite_text($temp_text)
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
        $new_value = make_date_new_val_pt($value);
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


function fix_cites_text($temp_text)
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
            $new_value = make_date_new_val_pt($value);
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

function pt_months($text)
{
    // ---
    // echo_test("pt_months:");
    // ---
    $citations = getCitations($text);
    // ---
    $new_text = $text;
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $cite_temp = $citation->getContent();
        // ---
        // echo_debug($cite_temp);
        // ---
        // if $cite_temp startwith {{ and ends with }}
        // if (start_end($cite_temp) || defined("DEBUG") || True) {
        // ---
        // echo_debug("\n$cite_temp\n");
        // ---
        $new_temp = fix_cites_text($cite_temp);
        // $new_temp = fix_one_cite_text($cite_temp);
        // ---
        // if ($new_temp != $cite_temp) echo_debug("new_temp != cite_temp\n");
        // ---
        $new_text = str_replace($cite_temp, $new_temp, $new_text);
        // } else {
        //     // ---
        //     echo_debug("temp not okay: $cite_temp\n");
        // }
    }
    // ---
    return $new_text;
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

function pt_fixes($text)
{
    // ---
    $text = pt_months($text);
    // ---
    $text = rm_ref_spaces($text);
    // ---
    return $text;
}
