<?php

namespace WpRefs\Parse\Category;


function get_categories_reg(string $text): array
{
    $categories = array();

    // This regular expression uses recursion (?R) to correctly handle nested brackets.
    // (?R) matches the entire pattern again, allowing it to match nested structures like [[...[...]...]].
    $pattern = "/\[\[\s*Category\s*:([^\]\]]+?)\]\]/is";
    // $pattern = "/\[\[\s*Category\s*:(.*?)\]\](?!\])/is";

    preg_match_all($pattern, $text, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[0] as $i => $full_match) {
            $category_content = $matches[1][$i];
            // Split the content based on "|" to retrieve only the category name
            $parts = explode('|', $category_content);
            $category_name = trim(array_shift($parts));

            // Use the full match as the value in the final array
            $categories[$category_name] = $full_match;
        }
    }

    return $categories;
}
