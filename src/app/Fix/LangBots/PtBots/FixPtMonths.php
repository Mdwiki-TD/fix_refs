<?php

namespace App\Fix\LangBots\PtBots;

use App\Fix\Bots\MonthsNewValue;
use App\Logger;
use App\Fix\Parse\Citations;
use App\Fix\WikiParse\ParserTemplates;

class FixPtMonths
{
    public static function start_end($citeTemp)
    {
        return strpos($citeTemp, "{{") === 0 && strrpos($citeTemp, "}}") === strlen($citeTemp) - 2;
    }

    public static function rm_ref_spaces($newtext)
    {
        $dot = "(\.|,|。|।)";
        $regline = "((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)";
        $pattern = "/\s*" . $dot . "\s*" . $regline . "/m";
        $replacement = "$1$2";
        $newtext = preg_replace($pattern, $replacement, $newtext);
        return $newtext;
    }

    public static function fix_pt_months_in_texts($tempText)
    {
        $newText = $tempText;
        $tempText = trim($tempText);
        $temps = (new ParserTemplates($tempText))->getTemplates();
        foreach ($temps as $temp) {
            $tempOld = $temp->getOriginalText();
            $params = $temp->getParameters();
            foreach ($params as $key => $value) {
                $newValue = MonthsNewValue::make_date_new_val_pt($value);
                if ($newValue !== null && trim((string)$newValue) !== trim((string)$value)) {
                    $temp->setParameter($key, $newValue);
                }
            }
            $tempNew = $temp->toString();
            $newText = str_replace($tempOld, $tempNew, $newText);
        }
        return $newText;
    }

    public static function fix_pt_months_in_refs($text)
    {
        Logger::debug("\n fix_pt_months_in_refs:\n");
        $newText = $text;
        $citations = Citations::getCitationsOld($text);
        foreach ($citations as $key => $citation) {
            $citeTemp = $citation->getContent();
            $newTemp = self::fix_pt_months_in_texts($citeTemp);
            $newText = str_replace($citeTemp, $newTemp, $newText);
        }
        return $newText;
    }

    public static function pt_fixes($text)
    {
        $text = self::fix_pt_months_in_refs($text);
        $text = self::rm_ref_spaces($text);
        return $text;
    }
}
