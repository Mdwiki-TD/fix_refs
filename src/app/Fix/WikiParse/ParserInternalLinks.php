<?php

namespace App\Fix\WikiParse;

use App\Fix\WikiParse\DataModel\InternalLink;

/**
 * Class ParserInternalLinks
 * @package App\Fix\WikiParse
 */
class ParserInternalLinks
{
    /**
     * @var string The text to parse
     */
    private string $text;

    /**
     * @var InternalLink[] The parsed pages
     */
    private array $targets = [];

    /**
     * ParserInternalLinks constructor.
     * @param string $text The text to parse
     */
    public function __construct(string $text)
    {
        $this->text = $text;
        $this->parse();
    }

    /**
     * Find all internal links in the given string
     * @param string $string The string to search for internal links
     * @return array<int, mixed> An array with two elements.
     */
    private function find_sub_links(string $string): array
    {
        preg_match_all("/\[{2}((?>[^\[\]]+)|(?R))*\]{2}/xu", $string, $matches);
        return $matches;
    }

    /**
     * Parse the text for internal links
     */
    public function parse(): void
    {
        $textLinks = $this->find_sub_links($this->text);
        if (isset($textLinks[0]) && is_array($textLinks[0])) {
            foreach ($textLinks[0] as $textLink) {
                if (preg_match("/^\[\[(.*?)(\]\])$/su", (string)$textLink, $matches)) {
                    $parts = explode("|", $matches[1], 2);
                    $_InternalLink = (count($parts) == 1) ? new InternalLink($parts[0]) : new InternalLink($parts[0], $parts[1]);
                    $this->targets[] = $_InternalLink;
                }
            }
        }
    }

    /**
     * Get all internal links found in the text
     * @return InternalLink[] An array of InternalLink objects
     */
    public function getTargets(): array
    {
        return $this->targets;
    }
}
