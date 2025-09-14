<?php

use FixRefs\Tests\MyFunctionTest;
use function WpRefs\MissingRefs\fix_missing_refs;

class missing_refsTest extends MyFunctionTest
{

    public function testPart1()
    {
        $input = 'Accreta, <ref name=\'Stat2020\'/> increta, percreta<ref name="Stat2020"/>';
        // ---
        $expected = 'Accreta, <ref name=\'Stat2020\'/> increta, percreta<ref name=Stat2020>{{cite journal |last1=Shepherd |first1=Alexa M. |last2=Mahdy |first2=Heba |title=Placenta Accreta |journal=StatPearls |date=2020 |pmid=33085435 |url=https://pubmed.ncbi.nlm.nih.gov/33085435/ |publisher=StatPearls Publishing |access-date=2020-10-23 |archive-date=2021-08-28 |archive-url=https://web.archive.org/web/20210828225644/https://pubmed.ncbi.nlm.nih.gov/33085435/ |url-status=live }} {{Webarchive|url=https://web.archive.org/web/20210828225644/https://pubmed.ncbi.nlm.nih.gov/33085435/ |date=2021-08-28 }}</ref>';
        // ---
        $this->assertEqualCompare($expected, $input, fix_missing_refs($input, '', 1469242));
    }
}
