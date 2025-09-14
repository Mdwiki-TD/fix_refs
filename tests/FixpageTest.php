<?php

use App\Tests\MyFunctionTest;
use function WpRefs\WprefText\fix_page;

class FixpageTest extends MyFunctionTest
{

    private function fix_page_wrap(string $text, string $lang)
    {
        return fix_page($text, "", true, true, false, $lang, "", 0);
    }

    public function testPart1()
    {
        $input = '[[Category:Translated from MDWiki]] ռետինոիդներ։ <ref name="NORD2006" /><ref name="Gli2017" />';
        // ---
        $expected = '[[Category:Translated from MDWiki]] ռետինոիդներ<ref name="NORD2006" /><ref name="Gli2017" />։';
        // ---
        $this->assertEqualCompare($expected, $input, $this->fix_page_wrap($input, 'hy'));
    }
}
