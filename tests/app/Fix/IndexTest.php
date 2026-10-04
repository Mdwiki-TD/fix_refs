<?php

use Tests\MyFunctionTest;
use App\Fix\Index;

class indexTest extends MyFunctionTest
{

    private function fixPageWrap(string $text, string $lang, bool $moveDots, bool $infobox, bool $addEnLang): string
    {
        return Index::fixPage($text, "title", $moveDots, $infobox, $addEnLang, $lang, 'SomeTitle', 0);
    }

    public function testPart1(): void
    {
        $input     = (string)file_get_contents(__DIR__ . "/fixtures/indexTest/1/input.txt");
        $expected  = (string)file_get_contents(__DIR__ . "/fixtures/indexTest/1/expected.txt");
        // --
        $result = $this->fixPageWrap($input, 'hy', true, true, true);
        // --
        $outputFile   = __DIR__ . "/fixtures/indexTest/1/output.txt";
        file_put_contents($outputFile, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testPart2(): void
    {
        $input     = (string)file_get_contents(__DIR__ . "/fixtures/indexTest/2/input.txt");
        $expected  = (string)file_get_contents(__DIR__ . "/fixtures/indexTest/2/expected.txt");
        // --
        $result = $this->fixPageWrap($input, 'hy', true, true, true);
        // --
        $outputFile   = __DIR__ . "/fixtures/indexTest/2/output.txt";
        file_put_contents($outputFile, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testPart3(): void
    {
        $input = '[[Category:Translated from MDWiki]] ռետինոիդներ։ <ref name="NORD2006" /><ref name="Gli2017" />';

        $expected = '[[Category:Translated from MDWiki]] ռետինոիդներ<ref name="NORD2006" /><ref name="Gli2017" />։';
        $this->assertEqualCompare($expected, $input, $this->fixPageWrap($input, 'hy', true, true, true));
    }

    public function testPart4(): void
    {
        $input = '[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ <ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։';

        $expected = '[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ<ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։';

        $this->assertEqualCompare($expected, $input, $this->fixPageWrap($input, 'hy', true, true, true));
    }
}
