<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\Infobox2\do_comments;
use function WpRefs\Infobox2\make_tempse;
use function WpRefs\Infobox2\expend_new;

class infobox2Test extends MyFunctionTest
{
    public function test_expend_new_FileText()
    {
        $textInput   = file_get_contents(__DIR__ . "/texts_infobox2/infobox2_input.txt");
        $textOutput  = file_get_contents(__DIR__ . "/texts_infobox2/infobox2_output.txt");
        $file_3  = __DIR__ . "/texts_infobox2/infobox2_fixed.txt";
        // --
        $result = expend_new($textInput);
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $textOutput = preg_replace("/\r\n/", "\n", $textOutput);
        // --
        file_put_contents($file_3, $result);
        // --
        $this->assertEquals(trim($textOutput), trim($result), "Unexpected result");
    }
    public function test_make_tempse_FileText()
    {
        $textInput   = file_get_contents(__DIR__ . "/texts_infobox2/infobox2_tempse_input.txt");
        $textOutput  = json_decode(file_get_contents(__DIR__ . "/texts_infobox2/infobox2_tempse_output.json"), true);
        // --
        $file_3  = __DIR__ . "/texts_infobox2/infobox2_tempse_fixed.json";
        // --
        $result = make_tempse($textInput);
        // --
        file_put_contents($file_3, json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // --
        $this->assertEquals($textOutput, $result, "Unexpected result");
    }
}
