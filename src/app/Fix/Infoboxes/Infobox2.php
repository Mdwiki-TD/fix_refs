<?php

namespace App\Infobox2;

use function App\Fix\Bots\TxtLib2\extract_templates_and_params;
use App\Fix\WikiParse\ParserTemplate;

function do_comments($text)
{
    $pattern = '/\s*\n*\s*(<!-- (Monoclonal antibody data|External links|Names*|Clinical data|Legal data|Legal status|Pharmacokinetic data|Chemical and physical data|Definition and medical uses|Chemical data|\w+ \w+ data|\w+ \w+ \w+ data|\w+ data|\w+ status|Identifiers) -->)\s*\n*/s';
    preg_match_all($pattern, $text, $matches);

    foreach ($matches[0] as $match) {
        $match2 = trim($match);
        $text = str_replace($match, "\n\n$match2\n", $text);
    }

    return $text;
}
function expend_new($mainTemp)
{
    // ---
    $mainTemp = trim($mainTemp);
    // ---
    $parser = new ParserTemplate($mainTemp);
    // ---
    $temp = $parser->getTemplate();
    // ---
    $newTemp = $temp->toString($newLine = true, $ljust = 17);
    // ---
    $newTemp = do_comments($newTemp);
    // ---
    $newTemp = trim($newTemp);
    // ---
    return $newTemp;
}

function make_tempse($section_0)
{
    $tempseBy_u = [];
    $tempse = [];

    $ingr = extract_templates_and_params($section_0);
    $u = 0;

    foreach ($ingr as $temp) {
        $u++;
        $tmpName = $temp['name'];
        $params = $temp['params'];
        $template = $temp['item'];

        if (count($params) > 4 && strpos($section_0, ">$template") === false) {
            $tempseBy_u[$u] = $temp;
            $tempse[$u] = strlen($template);
            // ---
            // print_s($namestrip);
        }
    }
    // ---
    return [
        "tempse_by_u" => $tempseBy_u,
        "tempse" => $tempse,
    ];
}
