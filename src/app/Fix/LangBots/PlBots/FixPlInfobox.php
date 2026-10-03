<?php

namespace App\Fix\LangBots\PlBots;

use App\Fix\DebugHelper;
use App\Fix\WikiParse\Template;

class FixPlInfobox
{
    public static function add_missing_params_to_choroba_infobox($text)
    {
        DebugHelper::debug("\n add_missing_params_to_choroba_infobox:\n");
        $newText = $text;
        $temps = Template::getTemplates($text);
        $paramsToAdd = [
            "nazwa naukowa" => "",
            "ICD11" => "",
            "ICD11 nazwa" => "",
            "ICD10" => "",
            "ICD10 nazwa" => "",
            "DSM-5" => "",
            "DSM-5 nazwa" => "",
            "DSM-IV" => "",
            "DSM-IV nazwa" => "",
            "ICDO" => "",
            "DiseasesDB" => "",
            "OMIM" => "",
            "MedlinePlus" => "",
            "MeshID" => "",
            "commons" => "",
        ];

        foreach ($temps as $temp) {
            $name = $temp->getStripName();
            if (strtolower($name) === "choroba infobox") {
                DebugHelper::debug("Found Choroba infobox template\n");
                $tempOld = $temp->getOriginalText();
                $params = $temp->getParameters();
                foreach ($paramsToAdd as $paramName => $paramValue) {
                    if (!array_key_exists($paramName, $params)) {
                        $temp->setParameter($paramName, $paramValue);
                    }
                }
                $tempNew = $temp->toString();
                $newText = str_replace($tempOld, $tempNew, $newText);
            }
        }
        return $newText;
    }

    public static function pl_fixes($text)
    {
        $text = self::add_missing_params_to_choroba_infobox($text);
        return $text;
    }
}
