<?php

namespace WikiParse\Category;

include_once __DIR__ . '/include_it.php';

/*
Usage:

use function WikiParse\Category\get_categories;

*/

// include_once __DIR__ . '/../WikiParse/Category.php';

function get_categories($text)
{
    // $parser = new ParserCategories($text);
    // $categories = $parser->getCategories();
    // ---
    $categories = array();
    // ---
    preg_match_all("/\[\[\s*Category\s*\:([^\]\]]+?)\]\]/is", $text, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[0] as $u => $ca) {
            $mvalue = $matches[1][$u];
            $bleh = explode("|", $mvalue);
            $category = trim(array_shift($bleh));
            $bleh = null;
            $categories[$category] = $ca;
            // echo $ca . "<br>";
        }
    };
    return $categories;
}
