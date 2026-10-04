<?php

namespace App\Fix\LangBots\EsBots;

use App\Fix\Bots\MonthsNewValue;
use App\Logger;
use App\Fix\Parse\Citations;
use App\Fix\WikiParse\ParserTemplates;

class EsMonths
{
    public static function start_end(string $citeTemp): bool
    {
        return strpos($citeTemp, "{{") === 0 && strrpos($citeTemp, "}}") === strlen($citeTemp) - 2;
    }

    public static function fix_es_months_in_texts(string $tempText): string
    {
        $newText = $tempText;
        $tempText = trim($tempText);
        $temps = (new ParserTemplates($tempText))->getTemplates();
        foreach ($temps as $temp) {
            $tempOld = $temp->getOriginalText();

            $params = $temp->getParameters();
            foreach ($params as $key => $value) {
                $newValue = MonthsNewValue::make_date_new_val_es($value);

                if (trim($newValue) !== trim((string)$value)) {
                    $temp->setParameter((string)$key, $newValue);
                }
            }
            $tempNew = $temp->toString();
            $newText = str_replace($tempOld, $tempNew, $newText);
        }
        return $newText;
    }

    public static function fix_es_months_in_refs(string $text): string
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
