<?php

namespace WikiParse\Category;

include_once __DIR__ . '/include_it.php';

/*
Usage:

use function WikiParse\Category\get_categories_reg;

*/

function get_categories_reg($text)
{
    $categories = array();

    // هذا التعبير النمطي يستخدم العودية (?R) للتعامل مع الأقواس المتداخلة بشكل صحيح.
    // (?R) تطابق النمط بأكمله مرة أخرى، مما يسمح بمطابقة هياكل متداخلة مثل [[...[...]...]].
    $pattern = "/\[\[\s*Category\s*\:([^\]\]]+?)\]\]/is";
    // $pattern = "/\[\[\s*Category\s*:(.*?)\]\](?!\])/is";

    preg_match_all($pattern, $text, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[0] as $i => $full_match) {
            $category_content = $matches[1][$i];
            // الآن نقوم بتقسيم المحتوى بناءً على "|" للحصول على اسم التصنيف فقط
            $parts = explode('|', $category_content);
            $category_name = trim(array_shift($parts));

            // لا نزال نستخدم المطابقة الكاملة كقيمة في المصفوفة النهائية
            $categories[$category_name] = $full_match;
        }
    }

    return $categories;
}
