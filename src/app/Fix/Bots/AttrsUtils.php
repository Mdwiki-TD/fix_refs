<?php

namespace App\Fix\Bots;

class AttrsUtils
{
    /**
     * @param string $text
     * @return array<string, string>
     */
    public static function parseAttributes(string $text): array
    {
        $text = "<ref " . $text . ">";

        $attrfindTolerant = '/
            ((?<=[\'"\s\/])[^\s\/>][^\s\/=>]*)             # Attribute name
            (\s*=+\s*                                      # Equals sign(s)
            (
                \'[^\']*\'                                 # Value in single quotes
                |"[^"]*"                                   # Value in double quotes
                |(?![\'"])[^>\s]*                          # Unquoted value
            ))?
            (?:\s|\/(?!>))*                                # Trailing space or slash not followed by >
        /xu';
        $attributesArray = [];

        if (preg_match_all($attrfindTolerant, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $attrName = strtolower($match[1]);
                $attrValue = isset($match[3]) ? $match[3] : "";
                $attributesArray[$attrName] = $attrValue;
            }
        }

        return $attributesArray;
    }

    /**
     * @param string $text
     * @return array<string, string>
     */
    public static function getAttrs(string $text): array
    {
        $text = "<ref $text>";
        $attrfindTolerant = '/((?<=[\'"\s\/])[^\s\/>][^\s\/=>]*)(\s*=+\s*(\'[^\']*\'|"[^"]*"|(?![\'"])[^>\s]*))?(?:\s|\/(?!>))*/u';
        $attrs = [];

        if (preg_match_all($attrfindTolerant, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $attrName = strtolower($match[1]);
                $attrValue = isset($match[3]) ? $match[3] : "";
                $attrs[$attrName] = $attrValue;
            }
        }

        // var_export($attrs);

        return $attrs;
    }
}
