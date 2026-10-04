<?php
// es_refsTest.php

use Tests\MyFunctionTest;
use App\Fix\LangBots\EsBots\EsRefs;

class esRefsTest extends MyFunctionTest
{
    public function testFileText(): void  {
        $textInput   = file_get_contents(__DIR__ . "/fixtures/1/input.txt");
        $expected  = file_get_contents(__DIR__ . "/fixtures/1/expected.txt");
        $file_3  = __DIR__ . "/fixtures/1/output.txt";
        // --
        $result = EsRefs::mv_es_refs($textInput);
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $expected = preg_replace("/\r\n/", "\n", $expected);
        // --
        file_put_contents($file_3, $result);
        // --
        $this->assertEquals($expected, $result, "Unexpected result");
    }
}
