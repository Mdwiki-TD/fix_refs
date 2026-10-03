<?php

namespace App\Fix\Parse\Category;


function get_categories_reg(string $text): array
{
    $categories = array();

    // This regular expression uses recursion (?R) to correctly handle nested brackets.
    // (?R) matches the entire pattern again, allowing it to match nested structures like [[...[...]...]].
    $pattern = "/\[\[\s*Category\s*:([^\]\]]+?)\]\]/is";
    // $pattern = "/\[\[\s*Category\s*:(.*?)\]\](?!\])/is";

    preg_match_all($pattern, $text, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[0] as $i => $fullMatch) {
            $categoryContent = $matches[1][$i];
            // Split the content based on "|" to retrieve only the category name
            $parts = explode('|', $categoryContent);
            $categoryName = trim(array_shift($parts));

            // Use the full match as the value in the final array
            $categories[$categoryName] = $fullMatch;
        }
    }

    return $categories;
}
