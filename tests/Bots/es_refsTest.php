<?php

use App\Tests\MyFunctionTest;
use function WpRefs\Bots\es_refs\mv_es_refs;

class es_refsTest extends MyFunctionTest
{
    public function testFileText()
    {
        $text_input   = file_get_contents(__DIR__ . "/texts/es_refs_input.txt");
        $text_output  = file_get_contents(__DIR__ . "/texts/es_refs_output.txt");
        $file_3  = __DIR__ . "/texts/es_refs_output_fixed.txt";
        // --
        $result = mv_es_refs($text_input);
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $text_output = preg_replace("/\r\n/", "\n", $text_output);
        // --
        file_put_contents($file_3, $result);
        // --
        $this->assertEquals($text_output, $result, "Unexpected result");
    }
}
