<?php

namespace WikiConnect\ParseWiki;

class ParserCategories
{
    private array $categories;
    private string $namespace;
    private string $text;
    public function __construct(string $text, string $namespace = "")
    {
        $this->text = $text;
        $this->namespace = $namespace == "" ? "Category" : $namespace;
        $this->categories = array();
        $this->parse();
    }
    public function parse(): void
    {
        $categories = array();
        // if (preg_match_all("\[\[\s*" . $this->namespace . "\s*\:([^\]\]]+?)\]\]", $this->text, $matches)) {
        if (preg_match_all("/\[\[\s*" . $this->namespace . "\s*\:([^\]\]]+?)\]\]/", $this->text, $matches)) {

            foreach ($matches[1] as $index => $match) {
                $parts = explode("|", $match);
                $category = trim(array_shift($parts));
                $categories[md5($category)] = $category;
            }
        }

        if (!empty($categories)) {
            $this->categories = $categories;
        }
    }
    public function getCategories(): array
    {
        return $this->categories;
    }
}
