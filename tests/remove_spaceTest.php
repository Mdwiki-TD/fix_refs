<?php


use PHPUnit\Framework\TestCase;
use function WpRefs\RemoveSpace\remove_spaces_between_last_word_and_beginning_of_ref;

class remove_spaceTest extends TestCase
{
    private function assertEqualCompare(string $expected, string $input, string $result)
    {
        if ($result === $input && $result !== $expected) {
            $this->fail("No changes were made! The function returned the input unchanged:\n$result");
        } else {
            $this->assertEquals($expected, $result, "Unexpected result:\n$result");
        }
    }

    public function testRemoveSpaceEnd1()
    {
        $input = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից  <ref name="Os2018" />։';
        $expected = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից<ref name="Os2018" />։';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy'));
    }
    public function testRemoveSpaceEnd1stFile()
    {
        $input   = file_get_contents(__DIR__ . "/texts/remove_space_texts/1/input.txt");
        $expected   = file_get_contents(__DIR__ . "/texts/remove_space_texts/1/expected.txt");
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $output_file   = __DIR__ . "/texts/remove_space_texts/1/output.txt";
        file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd2ndFile()
    {
        $input   = file_get_contents(__DIR__ . "/texts/remove_space_texts/2/input.txt");
        $expected   = file_get_contents(__DIR__ . "/texts/remove_space_texts/2/expected.txt");
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $output_file   = __DIR__ . "/texts/remove_space_texts/2/output.txt";
        file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd3rdFile()
    {
        $input   = file_get_contents(__DIR__ . "/texts/remove_space_texts/3/input.txt");
        $expected   = file_get_contents(__DIR__ . "/texts/remove_space_texts/3/expected.txt");
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $output_file   = __DIR__ . "/texts/remove_space_texts/3/output.txt";
        file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd4thFile()
    {
        $input   = file_get_contents(__DIR__ . "/texts/remove_space_texts/4/input.txt");
        $expected   = file_get_contents(__DIR__ . "/texts/remove_space_texts/4/expected.txt");
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $output_file   = __DIR__ . "/texts/remove_space_texts/4/output.txt";
        file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
}
