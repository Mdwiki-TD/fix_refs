<?php

use FixRefs\Tests\MyFunctionTest;
use function WpRefs\Infobox2\make_tempse;
use function WpRefs\Infobox2\expend_new;

class infobox2Test extends MyFunctionTest
{
    public function test_expend_new_FileText()
    {
        $textInput   = file_get_contents(__DIR__ . "/fixtures/1/input.txt");
        $expected  = file_get_contents(__DIR__ . "/fixtures/1/expected.txt");
        $output_file  = __DIR__ . "/fixtures/1/output.txt";
        // --
        $result = expend_new($textInput);
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $expected = preg_replace("/\r\n/", "\n", $expected);
        // --
        file_put_contents($output_file, $result);
        // --
        $this->assertEquals(trim($expected), trim($result), "Unexpected result");
    }
    public function test_make_tempse_FileText()
    {
        $textInput   = file_get_contents(__DIR__ . "/fixtures/infobox2_tempse/input.txt");
        $expected  = json_decode(file_get_contents(__DIR__ . "/fixtures/infobox2_tempse/expected.json"), true);
        // --
        $output_file  = __DIR__ . "/fixtures/infobox2_tempse/output.json";
        // --
        $result = make_tempse($textInput);
        // --
        file_put_contents($output_file, json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // --
        $this->assertEquals($expected, $result, "Unexpected result");
    }
}
