<?php

namespace App\Fix\LangBots\PlBots\FixPlInfobox;


use App\Fix\WikiParse\ParserTemplates;
use App\Logger;

function add_missing_params_to_choroba_infobox($text)
{
    // ---
    Logger::debug("\n add_missing_params_to_choroba_infobox:\n");
    // ---
    $newText = $text;
    // ---
    // Get all templates
    $temps = (new ParserTemplates($text))->getTemplates();
    // ---
    // Parameters to add if missing
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
    // ---
    foreach ($temps as $temp) {
        // ---
        $name = $temp->getStripName();
        // ---
        // Check if template name matches "Choroba infobox" (case-insensitive)
        if (strtolower($name) === "choroba infobox") {
            // ---
            Logger::debug("Found Choroba infobox template\n");
            // ---
            $tempOld = $temp->getOriginalText();
            $params = $temp->getParameters();
            // ---
            // Add missing parameters
            foreach ($paramsToAdd as $paramName => $paramValue) {
                if (!array_key_exists($paramName, $params)) {
                    $temp->setParameter($paramName, $paramValue);
                }
            }
            // ---
            $tempNew = $temp->toString();
            // ---
            $newText = str_replace($tempOld, $tempNew, $newText);
            // ---
        }
    }
    // ---
    return $newText;
}

function pl_fixes($text)
{
    // ---
    $text = add_missing_params_to_choroba_infobox($text);
    // ---
    return $text;
}
