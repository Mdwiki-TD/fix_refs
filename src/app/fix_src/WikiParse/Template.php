<?php

namespace WikiParse\Template;

use WpRefs\WikiConnect\ParseWiki\ParserTemplate;
use WpRefs\WikiConnect\ParseWiki\ParserTemplates;

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
