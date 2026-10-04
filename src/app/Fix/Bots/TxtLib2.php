<?php

namespace App\Fix\Bots;

use App\Fix\WikiParse\ParserTemplates;

class TxtLib2
{
    public static function extract_templates_and_params($text)
    {
        $temps = [];
        $tempsIn = (new ParserTemplates($text))->getTemplates();
        foreach ($tempsIn as $temp) {
            $name = $temp->getStripName();
            $textTemplate = $temp->getOriginalText();
            $params = $temp->getParameters();
            $temps[] = [
                "name" => $name,
                "item" => $textTemplate,
                "params" => $params,
            ];
        }
        return $temps;
    }
}
