<?php

namespace App\Fix\Infoboxes;

use App\Fix\Bots\TxtLib2;
use App\Fix\WikiParse\ParserTemplate;

class Infobox2
{
    public static function do_comments(string $text): string
    {
        $pattern = '/\s*\n*\s*(<!-- (Monoclonal antibody data|External links|Names*|Clinical data|Legal data|Legal status|Pharmacokinetic data|Chemical and physical data|Definition and medical uses|Chemical data|\w+ \w+ data|\w+ \w+ \w+ data|\w+ data|\w+ status|Identifiers) -->)\s*\n*/s';
        preg_match_all($pattern, $text, $matches);

        foreach ($matches[0] as $match) {
            $match2 = trim($match);
            $text = str_replace($match, "\n\n$match2\n", $text);
        }

        return $text;
    }

    public static function expend_new(string $mainTemp): string
    {
        $mainTemp = trim($mainTemp);

        $parser = new ParserTemplate($mainTemp);

        $temp = $parser->getTemplate();

        $newTemp = $temp->toString(true, 17);

        $newTemp = self::do_comments($newTemp);

        $newTemp = trim($newTemp);

        return $newTemp;
    }

    /**
     * @param string $section_0
     * @return array{tempse_by_u: array<int, array<string, mixed>>, tempse: array<int, int>}
     */
    public static function make_tempse(string $section_0): array
    {
        $tempseBy_u = [];
        $tempse = [];

        $ingr = TxtLib2::extract_templates_and_params($section_0);
        $u = 0;

        foreach ($ingr as $temp) {
            $u++;
            $tmpName = $temp['name'];
            $params = $temp['params'];
            $template = (string)$temp['item'];

            if (count($params) > 4 && strpos($section_0, ">$template") === false) {
                $tempseBy_u[$u] = $temp;
                $tempse[$u] = strlen($template);
            }
        }

        return [
            "tempse_by_u" => $tempseBy_u,
            "tempse" => $tempse,
        ];
    }
}
