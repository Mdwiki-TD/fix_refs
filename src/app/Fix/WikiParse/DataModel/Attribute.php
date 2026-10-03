<?php

namespace App\Fix\WikiParse\DataModel;

class Attribute
{
    /**
     * @var string The attribute of the citation.
     */
    private string $content;
    /**
     * @var array The attributes of the citation.
     */
    private array $attributesArray = [];

    /**
     * Attribute constructor.
     *
     * Initializes the Attribute object with the given content and parses
     * the attributes from it.
     *
     * @param string $content The content containing attributes.
     */

    public function __construct(string $content)
    {
        $this->content = $content;
        $this->parseAttributes();
    }

    /**
     * Set the attributes of the citation.
     *
     * @param string $content The attributes of the citation.
     *
     * @return void
     */

    public function setContent($content): void
    {
        $this->content = $content;
        $this->parseAttributes();
    }
    /**
     * Parse the attributes of the citation and store them in an array.
     *
     * Uses a regular expression to parse the attributes of the citation.
     */
    private function parseAttributes(): void
    {
        // <ref name="source" group="bar">This is a citation</ref>

        $text = "<ref " . $this->content . ">";
        // $attrfindTolerant = '/((?<=[\'"\s\/])[^\s\/>][^\s\/=>]*)(\s*=+\s*(\'[^\']*\'|"[^"]*"|(?![\'"])[^>\s]*))?(?:\s|\/(?!>))*/';

        $attrfindTolerant = '/
            ((?<=[\'"\s\/])[^\s\/>][^\s\/=>]*)             # Attribute name
            (\s*=+\s*                                      # Equals sign(s)
            (
                \'[^\']*\'                                 # Value in single quotes
                |"[^"]*"                                   # Value in double quotes
                |(?![\'"])[^>\s]*                          # Unquoted value
            ))?
            (?:\s|\/(?!>))*                                # Trailing space or slash not followed by >
        /x';
        $this->attributesArray = [];

        if (preg_match_all($attrfindTolerant, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $attrName = strtolower($match[1]);
                $attrValue = isset($match[3]) ? $match[3] : "";
                $this->attributesArray[$attrName] = $attrValue;
            }
        }
    }
    /**
     * Get the parsed attributes as an associative array.
     *
     * @return array An associative array of attributes where the key is the attribute name and the value is the attribute value.
     */

    public function getAttributesArray(): array
    {
        return $this->attributesArray;
    }

    /**
     * Check if an attribute exists in the citation.
     *
     * @param string $key The name of the attribute to check.
     *
     * @return bool True if the attribute exists, false otherwise.
     */

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributesArray);
    }
    /**
     * Get the value of an attribute of the citation.
     *
     * @param string $key The name of the attribute to get.
     * @param string $default The default value to return if the attribute does not exist.
     *
     * @return string The value of the attribute. If the attribute does not exist, the default value is returned.
     */

    public function get(string $key, string $default = ""): string
    {
        return $this->attributesArray[$key] ?? $default;
    }

    /**
     * Set an attribute of the citation.
     *
     * @param string $key The name of the attribute to set.
     * @param string $value The value of the attribute to set.
     *
     * @return void
     */

    public function set(string $key, string $value): void
    {
        $this->attributesArray[$key] = $value;
    }
    /**
     * Delete an attribute of the citation.
     *
     * @param string $key The name of the attribute to delete.
     *
     * @return void
     */

    /**
     * Delete an attribute from the citation.
     *
     * @param string $key The name of the attribute to delete.
     *
     * @return void
     */

    public function delete(string $key): void
    {
        if (array_key_exists($key, $this->attributesArray)) {
            unset($this->attributesArray[$key]);
        }
    }

    /**
     * Convert the attributes to a string.
     *
     * @param bool $addQuotes If true, add quotes to the attribute values.
     *
     * @return string The attributes as a string.
     */
    public function toString($addQuotes = false): string
    {
        $result = [];

        foreach ($this->attributesArray as $key => $value) {
            if (!$value) {
                $result[] = $key;
                continue;
            }
            if ($addQuotes) {
                // Remove quotes only if they match at the beginning and end
                if (strlen($value) > 1) {
                    $q = $value[0] ?? '';
                    if (($q === '"' || $q === "'") && str_ends_with($value, $q)) {
                        $value = substr($value, 1, -1);
                    }
                }
                $value = '"' . $value . '"';
            }
            $result[] = $key . '=' . $value;
        }

        return implode(' ', $result);
    }
    public function __toString(): string
    {
        return $this->toString();
    }
}
