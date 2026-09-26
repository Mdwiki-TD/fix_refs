<?php

namespace WikiParse\Template;

use WikiConnect\ParseWiki\ParserTemplate;
use WikiConnect\ParseWiki\ParserTemplates;

function getTemplate($text)
{
    $parser = new ParserTemplate($text);
    $temp = $parser->getTemplate();
    return $temp;
}

function getTemplates($text)
{
    if (empty($text)) {
        return [];
    }
    $parser = new ParserTemplates($text);
    $temps = $parser->getTemplates();
    return $temps;
}
