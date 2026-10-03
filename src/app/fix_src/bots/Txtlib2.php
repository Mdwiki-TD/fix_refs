<?php

namespace App\fix_src\bots;

use App\fix_src\WikiParse\Template;

class Txtlib2
{
    public static function extract_templates_and_params($text)
    {
        $temps = [];
        $tempsIn = Template::getTemplates($text);
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
