<?php

namespace App\Fix\Parse;

class CitationsReg
{
    /**
     * Get the name attribute from citation options.
     *
     * @param string $options The citation options string to extract name from
     * @return string The extracted name or empty string if not found
     */
    public static function get_name(string $options): string
    {
        if (trim($options) == "") {
            return "";
        }
        $pa = "/name\s*\=\s*[\"\']*([^>\"\']*)[\"\']*\s*/iu";
        preg_match($pa, $options, $matches);
        if (!isset($matches[1])) {
            return "";
        }
        $name = trim($matches[1]);
        return $name;
    }

    /**
     * @param string $text
     * @return array<int, array{content: string, tag: string, name: string, options: string}>
     */
    public static function get_regex_citations(string $text): array
    {
        preg_match_all("/<ref([^\/>]*?)>(.+?)<\/ref>/isu", $text, $matches);
        $citations = [];
        foreach ($matches[1] as $key => $citationOptions) {
            $content = (string)$matches[2][$key];
            $refTag = (string)$matches[0][$key];
            $options = (string)$citationOptions;
            $citation = [
                "content" => $content,
                "tag" => $refTag,
                "name" => self::get_name($options),
                "options" => $options
            ];
            $citations[] = $citation;
        }

        return $citations;
    }

    /**
     * @param string $text
     * @return array<string, string>
     */
    public static function get_full_refs(string $text): array
    {
        $full = [];
        $citations = self::get_regex_citations($text);
        foreach ($citations as $cite) {
            $name = $cite["name"];
            $ref = $cite["tag"];
            $full[$name] = $ref;
        }
        return $full;
    }

    /**
     * @param string $text
     * @return array<int, array{content: string, tag: string, name: string, options: string}>
     */
    public static function get_short_citations(string $text): array
    {
        preg_match_all("/<ref ([^\/>]*?)\/\s*>/isu", $text, $matches);
        $citations = [];
        foreach ($matches[1] as $key => $citationOptions) {
            $refTag = (string)$matches[0][$key];
            $options = (string)$citationOptions;
            $citation = [
                "content" => "",
                "tag" => $refTag,
                "name" => self::get_name($options),
                "options" => $options
            ];
            $citations[] = $citation;
        }

        return $citations;
    }
}
