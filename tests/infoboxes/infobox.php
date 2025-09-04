<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;

use function WpRefs\Infobox\Expend_Infobox;
use function WpRefs\Infobox\fix_title_bold;
use function WpRefs\Infobox\make_section_0;
use function WpRefs\Infobox\make_main_temp;
use function WpRefs\Infobox\find_max_value_key;

class es_refsTest extends TestCase
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
