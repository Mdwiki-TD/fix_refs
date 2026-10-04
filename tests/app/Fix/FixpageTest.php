<?php

use Tests\MyFunctionTest;
use App\Fix\Index;

class FixpageTest extends MyFunctionTest
{
    private function fixPageWrap(string $text, string $lang): string
    {
        return Index::fixPage($text, "", true, true, false, $lang, "", 0);
    }

    public function testPart1(): void
    {
        $input = '[[Category:Translated from MDWiki]] ռետինոիդներ։ <ref name="NORD2006" /><ref name="Gli2017" />';

        $expected = '[[Category:Translated from MDWiki]] ռետինոիդներ<ref name="NORD2006" /><ref name="Gli2017" />։';

        $this->assertEqualCompare($expected, $input, $this->fixPageWrap($input, 'hy'));
    }
}
