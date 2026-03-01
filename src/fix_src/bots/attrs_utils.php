<?php

namespace WpRefs\Bots\AttrsUtils;

/*

Usage:
use function WpRefs\Bots\AttrsUtils\parseAttributes;
use function WpRefs\Bots\AttrsUtils\get_attrs;

*/

function parseAttributes($text): array
{
    $text = "<ref " . $text . ">";

    $attrfind_tolerant = '/
            ((?<=[\'"\s\/])[^\s\/>][^\s\/=>]*)             # Attribute name
            (\s*=+\s*                                      # Equals sign(s)
            (
                \'[^\']*\'                                 # Value in single quotes
                |"[^"]*"                                   # Value in double quotes
                |(?![\'"])[^>\s]*                          # Unquoted value
            ))?
            (?:\s|\/(?!>))*                                # Trailing space or slash not followed by >
        /xu';
    $attributes_array = [];

    if (preg_match_all($attrfind_tolerant, $text, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $attr_name = strtolower($match[1]);
            $attr_value = isset($match[3]) ? $match[3] : "";
            $attributes_array[$attr_name] = $attr_value;
        }
    }

    return $attributes_array;
}

function get_attrs($text)
{
    $text = "<ref $text>";
    $attrfind_tolerant = '/((?<=[\'"\s\/])[^\s\/>][^\s\/=>]*)(\s*=+\s*(\'[^\']*\'|"[^"]*"|(?![\'"])[^>\s]*))?(?:\s|\/(?!>))*/u';
    $attrs = [];

    if (preg_match_all($attrfind_tolerant, $text, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $attr_name = strtolower($match[1]);
            $attr_value = isset($match[3]) ? $match[3] : "";
            $attrs[$attr_name] = $attr_value;
        }
    }
    // ---
    // var_export($attrs);
    // ---
    return $attrs;
}
