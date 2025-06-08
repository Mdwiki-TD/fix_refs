<?php

namespace WpRefs\Bots\es_months;
/*
(January|February|March|April|May|June|July|August|September|October|November|December)
usage:

use function WpRefs\Bots\es_months\make_new_val;
use function WpRefs\Bots\es_months\fix_es_months;
*/

// use function WikiParse\Citations\get_full_refs;
use function WikiParse\Citations\getCitations;
use function WikiParse\Template\getTemplate;
use function WikiParse\Template\getTemplates;
use function WpRefs\TestBot\echo_debug;

// Define the Spanish month translations
$es_months_tab = [
    "January" => "enero",
    "February" => "febrero",
    "March" => "marzo",
    "April" => "abril",
    "May" => "mayo",
    "June" => "junio",
    "July" => "julio",
    "August" => "agosto",
    "September" => "septiembre",
    "October" => "octubre",
    "November" => "noviembre",
    "December" => "diciembre",
];

$es_months_lower = array_change_key_case($es_months_tab, CASE_LOWER);

function fix_one_cite_temp($temp_text)
{
    // ---
    $temp_text = trim($temp_text);
    // ---
    $temp = getTemplate($temp_text);
    // ---
    $params = $temp->getParameters();
    // ---
    foreach ($params as $key => $value) {
        // ---
        $new_value = make_new_val($value);
        // ---
        if ($new_value && $new_value != $value) {
            $temp->setParameter($key, $new_value);
        }
    }
    // ---
    $new_text = $temp->toString();
    // ---
    return $new_text;
}

function fix_cites_text($temp_text)
{
    // ---
    $new_text = $temp_text;
    // ---
    $temp_text = trim($temp_text);
    // ---
    $temps = getTemplates($temp_text);
    // ---
    foreach ($temps as $temp) {
        // ---
        $temp_old = $temp->getTemplateText();
        // ---
        // echo_debug("temp_old:($temp_old)\n");
        // ---
        $params = $temp->getParameters();
        // ---
        foreach ($params as $key => $value) {
            // ---
            $new_value = make_new_val($value);
            // ---
            if ($new_value && $new_value != $value) {
                $temp->setParameter($key, $new_value);
            }
        }
        // ---
        $temp_new = $temp->toString();
        // ---
        $new_text = str_replace($temp_old, $temp_new, $new_text);
        // ---
    }
    return $new_text;
}
function start_end($cite_temp)
{
    return strpos($cite_temp, "{{") === 0 && strrpos($cite_temp, "}}") === strlen($cite_temp) - 2;
}

function fix_es_months($text)
{
    // ---
    echo_debug("\nfix_es_months:\n");
    // ---
    $new_text = $text;
    // ---
    $citations = getCitations($text);
    // ---
    foreach ($citations as $key => $citation) {
        // ---
        $cite_temp = $citation->getTemplate();
        // ---
        echo_debug("\ncite_temp: $cite_temp\n");
        // ---
        // if $cite_temp startwith {{ and ends with }}
        // if (start_end($cite_temp) || defined("DEBUG") || True) {
        // ---
        // $new_temp = fix_one_cite_temp($cite_temp);
        $new_temp = fix_cites_text($cite_temp);
        // ---
        if ($new_temp != $cite_temp) {
            echo_debug("new_temp != cite_temp\n");
        }
        // ---
        $new_text = str_replace($cite_temp, $new_temp, $new_text);
        // }
    }
    // ---
    return $new_text;
}

function make_new_val($val)
{
    global $es_months_lower;
    // ---
    $newVal = $val;
    // ---
    $patterns = [
        // Match date like: January, 2020 or 10 January, 2020
        '/^(?P<d>\d{1,2} |)(?P<m>January|February|March|April|May|June|July|August|September|October|November|December),* (?P<y>\d{4})$/',
        // Match date like: January 10, 2020
        '/^(?P<m>January|February|March|April|May|June|July|August|September|October|November|December) (?P<d>\d{1,2}),* (?P<y>\d{4})$/',
    ];

    foreach ($patterns as $pattern) {
        preg_match($pattern, trim($val), $matches);
        // ---
        if ($matches) {
            $day = trim($matches['d']);
            $month = trim($matches['m']);
            $year = trim($matches['y']);
            // ---
            // echo_test("day:$val\n");
            // echo_test("day:$day, month:$month, year:$year\n");
            // ---
            $translatedMonth = $es_months_lower[strtolower($month)] ?? "";

            if (!empty($translatedMonth)) {
                if (!empty($day)) {
                    $translatedMonth = "de $translatedMonth"; // Prepend "de" for "of" in Portuguese
                }

                $newVal = "$day $translatedMonth de $year";
                return trim($newVal);
            }
        }
    }

    return trim($newVal);
}

// // Example usage
// $dateString = "10 January, 2024";
// $newDateString = make_new_val($dateString);
// echo $newDateString; // Output: 10 de janeiro de 2024
