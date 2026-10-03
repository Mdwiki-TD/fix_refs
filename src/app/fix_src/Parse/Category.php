<?php

namespace App\fix_src\Parse;

class Category
{
    public static function get_categories_reg(string $text): array
    {
        $categories = array();

        $pattern = "/\[\[\s*Category\s*:([^\]\]]+?)\]\]/is";

        preg_match_all($pattern, $text, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[0] as $i => $fullMatch) {
                $categoryContent = $matches[1][$i];
                $parts = explode('|', $categoryContent);
                $categoryName = trim(array_shift($parts));

                $categories[$categoryName] = $fullMatch;
            }
        }

        return $categories;
    }
}
