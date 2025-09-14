<?php



use PHPUnit\Framework\TestCase;
use function WpRefs\Infobox2\do_comments;
use function WpRefs\Infobox2\make_tempse;
use function WpRefs\Infobox2\expend_new;

class infobox2Test extends TestCase
{
    public function test_expend_new_FileText()
    {
        $text_input   = file_get_contents(__DIR__ . "/texts_infobox2/infobox2_input.txt");
        $text_output  = file_get_contents(__DIR__ . "/texts_infobox2/infobox2_output.txt");
        $file_3  = __DIR__ . "/texts_infobox2/infobox2_fixed.txt";
        // --
        $result = expend_new($text_input);
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $text_output = preg_replace("/\r\n/", "\n", $text_output);
        // --
        file_put_contents($file_3, $result);
        // --
        $this->assertEquals(trim($text_output), trim($result), "Unexpected result");
    }
    public function test_make_tempse_FileText()
    {
        $text_input   = file_get_contents(__DIR__ . "/texts_infobox2/infobox2_tempse_input.txt");
        $text_output  = json_decode(file_get_contents(__DIR__ . "/texts_infobox2/infobox2_tempse_output.json"), true);
        // --
        $file_3  = __DIR__ . "/texts_infobox2/infobox2_tempse_fixed.json";
        // --
        $result = make_tempse($text_input);
        // --
        file_put_contents($file_3, json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // --
        $this->assertEquals($text_output, $result, "Unexpected result");
    }
}
