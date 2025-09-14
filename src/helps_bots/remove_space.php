<?php

namespace WpRefs\RemoveSpace;

/*
usage:

use function WpRefs\RemoveSpace\remove_spaces_between_last_word_and_beginning_of_ref;

*/

function remove_spaces_between_last_word_and_beginning_of_ref($newtext, $lang)
{
    // --- تحديد علامات الترقيم
    $dot = "\.,。।";
    if ($lang === "hy") {
        $dot = "\.,।։";
    }

    // --- نبحث عن أي فقرة تنتهي بـ </ref> أو /> + علامات الترقيم
    // ونزيل أي مسافة قبل مجموعة المراجع
    $regline = "((?:\\s*<ref[\\s\\S]+?(?:<\\/ref|\\/)>)+)";
    $pattern = '/(\S)\s+(?=' . $regline . '[' . $dot . ']+\s*(?:\n\n|\z))/u';

    $newtext = preg_replace($pattern, '$1', $newtext);

    return $newtext;
}


function assertEqualCompare(string $expected, string $input, string $result)
{
    if ($result === $expected) {
        echo "result === expected";
    } elseif ($result === $input) {
        echo "result === input";
    } else {
        echo "result !== expected\n";
        echo $result;
    }
}

$input = <<<'TXT'
'''Հարլեկին տիպի իխտիոզը''' [[Գենետիկ հիվանդություններ|գենետիկ խանգարում]] է, որը ծննդյան պահին հանգեցնում է մաշկի հաստացման գրեթե ամբողջ մարմնով space here <ref name="GHR2008" /> <ref name="GHR2008">{{Cite web
|title=Harlequin ichthyosis
|url=https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|website=Genetics Home Reference
|access-date=July 18, 2017
|language=en
|date=November 2008
|url-status=live
|archive-url=https://web.archive.org/web/20170728015729/https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|archive-date=July 28, 2017
}}</ref> test  <ref name="GHR2008" /> <ref name="GHR2008">{{Cite web
|title=Harlequin ichthyosis
|url=https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|website=Genetics Home Reference
|access-date=July 18, 2017
|language=en
|date=November 2008
|url-status=live
|archive-url=https://web.archive.org/web/20170728015729/https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|archive-date=July 28, 2017
}}</ref>։
TXT;

$expected = <<<'TXT'
'''Հարլեկին տիպի իխտիոզը''' [[Գենետիկ հիվանդություններ|գենետիկ խանգարում]] է, որը ծննդյան պահին հանգեցնում է մաշկի հաստացման գրեթե ամբողջ մարմնով space here <ref name="GHR2008" /> <ref name="GHR2008">{{Cite web
|title=Harlequin ichthyosis
|url=https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|website=Genetics Home Reference
|access-date=July 18, 2017
|language=en
|date=November 2008
|url-status=live
|archive-url=https://web.archive.org/web/20170728015729/https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|archive-date=July 28, 2017
}}</ref> test<ref name="GHR2008" /> <ref name="GHR2008">{{Cite web
|title=Harlequin ichthyosis
|url=https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|website=Genetics Home Reference
|access-date=July 18, 2017
|language=en
|date=November 2008
|url-status=live
|archive-url=https://web.archive.org/web/20170728015729/https://ghr.nlm.nih.gov/condition/harlequin-ichthyosis
|archive-date=July 28, 2017
}}</ref>։
TXT;

$result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');

assertEqualCompare($expected, $input, $result);

$output = __DIR__ . "/output.txt";

// save $result to $output
file_put_contents($output, $result);

echo "\n saved to: $output";
