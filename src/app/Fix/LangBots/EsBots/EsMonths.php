<?php

namespace App\Fix\LangBots\EsBots\EsMonths;


use App\Logger;
use function App\Fix\Parse\Citations\getCitationsOld;
use App\Fix\WikiParse\ParserTemplates;
use function App\Fix\Bots\MonthNewValue\make_date_new_val_es;


function start_end($citeTemp)
{
    return strpos($citeTemp, "{{") === 0 && strrpos($citeTemp, "}}") === strlen($citeTemp) - 2;
}

function fix_es_months_in_texts($tempText)
{
    // ---
    $newText = $tempText;
    // ---
    $tempText = trim($tempText);
    // ---
    $temps = (new ParserTemplates($tempText))->getTemplates();
    // ---
    foreach ($temps as $temp) {
        // ---
        $tempOld = $temp->getOriginalText();
        // ---
        // Logger::debug("temp_old:($tempOld)\n");
        // ---
        $params = $temp->getParameters();
        // ---
        foreach ($params as $key => $value) {
            // ---
            $newValue = make_date_new_val_es($value);
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

function fix_es_months_in_refs($text)
{
    // ---
    Logger::debug("\n fix_es_months_in_refs:\n");
    // ---
    $newText = $text;
    // ---
    $citations = getCitationsOld($text);
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $citeTemp = $citation->getContent();
        // ---
        // Logger::debug("\n cite_temp: $citeTemp\n");
        // ---
        // if $citeTemp startwith {{ and ends with }}
        // if (start_end($citeTemp) || defined("DEBUG") || True) {
        // ---
        $newTemp = fix_es_months_in_texts($citeTemp);
        // ---
        // if ($newTemp != $citeTemp) Logger::debug("new_temp != cite_temp\n");
        // ---
        $newText = str_replace($citeTemp, $newTemp, $newText);
        // }
    }
    // ---
    return $newText;
}
