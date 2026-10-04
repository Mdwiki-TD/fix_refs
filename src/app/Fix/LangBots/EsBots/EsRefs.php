<?php

namespace App\Fix\LangBots\EsBots;

use App\Fix\Parse\Citations;
use App\Fix\Parse\CitationsReg;
use App\Fix\WikiParse\ParserTemplates;

class EsRefs
{
    public static function get_refs(string $text): array
    {
        $newText = $text;
        $refs = [];
        $citations = Citations::getCitationsOld($text);
        $newText = $text;
        $numb = 0;

        foreach ($citations as $key => $citation) {
            $citeText = $citation->getOriginalText();
            $citeContents = $citation->getContent();
            $citeAttrs = $citation->getAttributes();
            $citeAttrs = $citeAttrs ? trim($citeAttrs) : "";

            if (empty($citeAttrs)) {
                $numb += 1;
                $name = "autogen_" . $numb;
                $citeAttrs = "name='$name'";
            }

            $refs[$citeAttrs] = $citeContents;

            // Logger::debug("\n$citeAttrs\n");

            $citeNewtext = "<ref $citeAttrs />";
            $newText = str_replace($citeText, $citeNewtext, $newText);
        }

        return [
            "refs" => $refs,
            "new_text" => $newText,
        ];
    }

    public static function check_short_refs($line)
    {
        $shorts = CitationsReg::get_short_citations($line);
        foreach ($shorts as $short) {
            $line = str_replace($short["tag"], "", $line);
        }

        // remove \n+
        $line = preg_replace("/\n+/u", "\n", $line);
        return $line;
    }

    public static function make_line(array $refs): string
    {
        $line = "\n";
        foreach ($refs as $name => $ref) {
            $la = '<ref ' . trim($name) . '>' . $ref . '</ref>' . "\n";
            $line .= $la;
        }
        $line = trim($line);
        return $line;
    }

    public static function add_line_to_temp($line, $text)
    {
        $tempsIn = (new ParserTemplates($text))->getTemplates();

        // Logger::debug("lenth temps_in:" . count($tempsIn) . "\n");

        $newText = $text;
        $tempAlreadyIn = false;

        foreach ($tempsIn as $temp) {
            $name = $temp->getStripName();

            // Logger::debug("\n$name\n");

            $oldTextTemplate = $temp->getOriginalText();

            if (!in_array(strtolower($name), ["reflist", "listaref"])) {
                continue;
            }

            // Logger::debug("\n$name\n");

            $refnParam = $temp->getParameter("refs");

            if ($refnParam) {
                $refnParam = self::check_short_refs($refnParam);

                $line = trim($refnParam) . "\n" . trim($line);
            }

            $temp->setParameter("refs", "\n" . trim($line) . "\n");
            $tempAlreadyIn = true;
            $newTextStr = $temp->toString();
            $newText = str_replace($oldTextTemplate, $newTextStr, $newText);
            break;
        }

        if (!$tempAlreadyIn) {
            $sectionRef = "\n== Referencias ==\n{{listaref|refs=\n$line\n}}";
            $newText .= $sectionRef;
        }

        return $newText;
    }

    public static function mv_es_refs(string $text): string
    {
        if (empty($text)) {
            // Logger::debug("text is empty");
            return $text;
        }

        $refs = self::get_refs($text);
        $newLines = self::make_line($refs['refs']);
        $newText = $refs['new_text'];
        $newText = self::add_line_to_temp($newLines, $newText);

        return $newText;
    }
}
