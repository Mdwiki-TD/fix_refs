<?php

namespace App\fix_src\bots;

use App\fix_src\DebugHelper;
use App\fix_src\Parse\Citations;

class RemoveDuplicateRefs
{
    public static function fix_refs_names(string $text): string
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

            $attrs = AttrsUtils::get_attrs($citeAttrs);

            if (empty($citeAttrs)) {
                continue;
            }

            $newCiteAttrs = "";

            foreach ($attrs as $key => $value) {
                $value2 = RefsUtils::remove_start_end_quotes($value);
                $newCiteAttrs .= " $key=$value2";
            }

            $newCiteAttrs = trim($newCiteAttrs);
            $citeNewtext = "<ref $newCiteAttrs>";
            $newText = str_replace($ifIn, $citeNewtext, $newText);
        }

        return $newText;
    }

    public static function remove_Duplicate_refs_With_attrs(string $text): string
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

            DebugHelper::echo_debug("\n cite_attrs: (($citeAttrs))");
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
                $newText = preg_replace($pattern, $value, $newText, 1);
            }
        }

        return $newText;
    }
}
