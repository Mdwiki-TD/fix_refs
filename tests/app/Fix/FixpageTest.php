<?php

use Tests\MyFunctionTest;
use App\Fix\PageFixer;

class FixpageTest extends MyFunctionTest
{
    private function fixPageWrap(string $text, string $lang): string
    {
        return PageFixer::fixPage($text, "", true, true, false, $lang, "", 0);
    }

    public function testPart1(): void
    {
        $input = '[[Category:Translated from MDWiki]] ռետինոիդներ։ <ref name="NORD2006" /><ref name="Gli2017" />';

        $expected = '[[Category:Translated from MDWiki]] ռետինոիդներ<ref name="NORD2006" /><ref name="Gli2017" />։';

        $this->assertEqualCompare($expected, $input, $this->fixPageWrap($input, 'hy'));
    }
}
