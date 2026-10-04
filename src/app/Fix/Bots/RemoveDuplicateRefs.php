<?php

namespace App\Fix\Bots;

use App\Logger;
use App\Fix\Bots\AttrsUtils;
use App\Fix\Bots\RefsUtils;
use App\Fix\Parse\Citations;

class RemoveDuplicateRefs
{
    public static function fixRefsNames(string $text): string
    {
        $newText = $text;
        $citations = Citations::getCitationsOld($text);
        $newText = $text;

        foreach ($citations as $key => $citation) {
            $citeAttrs = $citation->getAttributes();
            $citeAttrs = $citeAttrs ? trim($citeAttrs) : "";
            $ifIn = "<ref $citeAttrs>";

            if (strpos($newText, $ifIn) === false) {
                continue;
            }

            $attrs = AttrsUtils::getAttrs($citeAttrs);

            if (empty($citeAttrs)) {
                continue;
            }

            $newCiteAttrs = "";

            foreach ($attrs as $attrKey => $value) {
                $value2 = RefsUtils::remove_start_end_quotes($value);
                $newCiteAttrs .= " $attrKey=$value2";
            }

            $newCiteAttrs = trim($newCiteAttrs);
            $citeNewtext = "<ref $newCiteAttrs>";
            $newText = str_replace($ifIn, $citeNewtext, $newText);
        }

        return $newText;
    }

    public static function removeDuplicateRefsWithAttrs(string $text): string
    {
        $newText = $text;
        $refsToCheck = [];
        $refs = [];
        $citations = Citations::getCitationsOld($newText);
        $numb = 0;

        foreach ($citations as $key => $citation) {
            $citeFulltext = $citation->getOriginalText();
            $citeAttrs = $citation->getAttributes();
            $citeAttrs = $citeAttrs ? trim($citeAttrs) : "";

            if (empty($citeAttrs)) {
                $numb += 1;
                $name = "autogen_" . $numb;
                $citeAttrs = "name='$name'";
            }

            // Logger::debug("\n cite_text: (($citeFulltext))");
            Logger::debug("\n cite_attrs: (($citeAttrs))");

            $citeNewtext = "<ref $citeAttrs />";

            if (isset($refs[$citeAttrs])) {

                $newText = str_replace($citeFulltext, $citeNewtext, $newText);
            } else {
                $refsToCheck[$citeNewtext] = $citeFulltext;

                $refs[$citeAttrs] = $citeNewtext;
            }
        }

        foreach ($refsToCheck as $key => $value) {
            if (strpos($newText, $value) === false) {
                $pattern = '/' . preg_quote($key, '/') . '/u';
                $newText = (string)preg_replace($pattern, $value, $newText, 1);
            }
        }

        // echo count($citations);

        return $newText;
    }
}
