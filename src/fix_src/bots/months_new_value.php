<?php

namespace WpRefs\Bots\MonthNewValue;
/*
usage:
use function WpRefs\Bots\MonthNewValue\make_date_new_val_pt;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_es;
*/

function new_date($val, $lang = 'pt')
{
    // Define month translations per language
    $months_translations = [
        'pt' => [
            "January" => "janeiro",
            "February" => "fevereiro",
            "March" => "março",
            "April" => "abril",
            "May" => "maio",
            "June" => "junho",
            "July" => "julho",
            "August" => "agosto",
            "September" => "setembro",
            "October" => "outubro",
            "November" => "novembro",
            "December" => "dezembro",
        ],
        'es' => [
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
        ],
    ];

    // Ensure language exists
    if (!isset($months_translations[$lang])) {
        return trim($val);
    }

    $months_lower = array_change_key_case($months_translations[$lang], CASE_LOWER);

    $month_part = "(?P<m>January|February|March|April|May|June|July|August|September|October|November|December)";
    $patterns = [
        "/^(?:(?P<d>\d{1,2})\s+)?$month_part,?\s+(?P<y>\d{4})$/iu",
        "/^$month_part\s+(?P<d>\d{1,2}),?\s+(?P<y>\d{4})$/iu",
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, trim($val), $matches)) {
            $day   = $matches['d'];
            $month = strtolower($matches['m']);
            $year  = $matches['y'];
            $translatedMonth = $months_lower[$month] ?? "";

            if ($translatedMonth) {
                // Build result depending on language
                if ($lang === 'es') {
                    return trim($day ? "$day de $translatedMonth de $year" : "$translatedMonth de $year");
                }
                // no de before year in pt
                return trim($day ? "$day de $translatedMonth $year" : "$translatedMonth $year");
            }
        }
    }
    return trim($val);
}

function make_date_new_val_pt($val)
{
    return new_date($val, 'pt');
}

function make_date_new_val_es($val)
{
    return new_date($val, 'es');
}
