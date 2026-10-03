<?php

namespace App\fix_src\bots;

use App\fix_src\Parse\CitationsReg;

class ExpendRefs
{
    public static function refs_expend_work($first, $alltext = "")
    {
        if (empty($alltext)) {
            $alltext = $first;
        }
        $refs = CitationsReg::get_full_refs($alltext);
        $shortRefs = CitationsReg::get_short_citations($first);

        foreach ($shortRefs as $cite) {
            $name = $cite["name"];
            $refe = $cite["tag"];
            $rr = $refs[$name] ?? false;
            if ($rr) {
                $first = str_replace($refe, $rr, $first);
            }
        }
        return $first;
    }
}
