<?php

namespace App\fix_src\WikiParse;

use App\fix_src\WikiParse\src\ParserTemplate;
use App\fix_src\WikiParse\src\ParserTemplates;

class Template
{
    public static function getTemplate($text)
    {
        $parser = new ParserTemplate($text);
        $temp = $parser->getTemplate();
        return $temp;
    }

    public static function getTemplates($text)
    {
        if (empty($text)) {
            return [];
        }
        $parser = new ParserTemplates($text);
        $temps = $parser->getTemplates();
        return $temps;
    }
}
