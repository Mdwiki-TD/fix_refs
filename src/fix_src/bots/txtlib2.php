<?php

namespace WpRefs\Bots\TxtLib2;

use function WikiParse\Template\getTemplate;
use function WikiParse\Template\getTemplates;


function extract_templates_and_params($text)
{
    // ---
    $temps = [];
    $temps_in = getTemplates($text);
    // ---
    foreach ($temps_in as $temp) {
        // ---
        $name = $temp->getStripName();
        // ---
        $text_template = $temp->getOriginalText();
        // ---
        $params = $temp->getParameters();
        // ---
        $temps[] = [
            "name" => $name,
            "item" => $text_template,
            "params" => $params,
        ];
        // ---
    }
    // ---
    return $temps;
}
