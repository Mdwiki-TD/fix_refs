<?php

namespace App\Fix\LangBots\EsBots;

use App\Fix\Bots\MonthsNewValue;
use App\Logger;
use App\Fix\Parse\Citations;
use App\Fix\WikiParse\Template;

class EsMonths
{
    public static function start_end($citeTemp)
    {
        return strpos($citeTemp, "{{") === 0 && strrpos($citeTemp, "}}") === strlen($citeTemp) - 2;
    }

    public static function fix_es_months_in_texts($tempText)
    {
        $newText = $tempText;
        $tempText = trim($tempText);
        $temps = Template::getTemplates($tempText);
        foreach ($temps as $temp) {
            $tempOld = $temp->getOriginalText();
            $params = $temp->getParameters();
            foreach ($params as $key => $value) {
                $newValue = MonthsNewValue::make_date_new_val_es($value);
                if ($newValue !== null && trim((string)$newValue) !== trim((string)$value)) {
                    $temp->setParameter($key, $newValue);
                }
            }
            $tempNew = $temp->toString();
            $newText = str_replace($tempOld, $tempNew, $newText);
        }
        return $newText;
    }

    public static function fix_es_months_in_refs($text)
    {
        Logger::debug("\n fix_es_months_in_refs:\n");
        $newText = $text;
        $citations = Citations::getCitationsOld($text);
        foreach ($citations as $key => $citation) {
            $citeTemp = $citation->getContent();
            $newTemp = self::fix_es_months_in_texts($citeTemp);
            $newText = str_replace($citeTemp, $newTemp, $newText);
        }
        return $newText;
    }
}
