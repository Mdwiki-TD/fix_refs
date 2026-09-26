<?php

namespace WpRefs\Bots\TxtLib2;

use function WikiParse\Template\getTemplate;
use function WikiParse\Template\getTemplates;


function extract_templates_and_params($text)
{
    // ---
    $temps = [];
    $tempsIn = getTemplates($text);
    // ---
    foreach ($tempsIn as $temp) {
        // ---
        $name = $temp->getStripName();
        // ---
        $textTemplate = $temp->getOriginalText();
        // ---
        $params = $temp->getParameters();
        // ---
        $temps[] = [
            "name" => $name,
            "item" => $textTemplate,
            "params" => $params,
        ];
        // ---
    }
    // ---
    return $temps;
}
