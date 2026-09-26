<?php

use FixRefs\Tests\MyFunctionTest;
use function WpRefs\EsBots\es_refs\mv_es_refs;

class es_refsTest extends MyFunctionTest
{
    public function testFileText()
    {
        $textInput   = file_get_contents(__DIR__ . "/texts/1/input.txt");
        $textOutput  = file_get_contents(__DIR__ . "/texts/1/expected.txt");
        $file_3  = __DIR__ . "/texts/1/output.txt";
        // --
        $result = mv_es_refs($textInput);
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $textOutput = preg_replace("/\r\n/", "\n", $textOutput);
        // --
        file_put_contents($file_3, $result);
        // --
        $this->assertEquals($textOutput, $result, "Unexpected result");
    }
}
