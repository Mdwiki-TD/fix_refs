<?php

namespace WpRefs\PL\FixPlInfobox;
/*
usage:

use function WpRefs\PL\FixPlInfobox\pl_fixes;

*/

use function WikiParse\Template\getTemplates;
use function WpRefs\TestBot\echo_test;

function add_missing_params_to_choroba_infobox($text)
{
    // ---
    echo_test("\n add_missing_params_to_choroba_infobox:\n");
    // ---
    $new_text = $text;
    // ---
    // Get all templates
    $temps = getTemplates($text);
    // ---
    // Parameters to add if missing
    $params_to_add = [
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
        if (strcasecmp($name, "Choroba infobox") === 0) {
            // ---
            echo_test("Found Choroba infobox template\n");
            // ---
            $temp_old = $temp->getOriginalText();
            $params = $temp->getParameters();
            // ---
            // Add missing parameters
            foreach ($params_to_add as $param_name => $param_value) {
                if (!array_key_exists($param_name, $params)) {
                    $temp->setParameter($param_name, $param_value);
                }
            }
            // ---
            $temp_new = $temp->toString();
            // ---
            $new_text = str_replace($temp_old, $temp_new, $new_text);
            // ---
        }
    }
    // ---
    return $new_text;
}

function pl_fixes($text)
{
    // ---
    $text = add_missing_params_to_choroba_infobox($text);
    // ---
    return $text;
}
