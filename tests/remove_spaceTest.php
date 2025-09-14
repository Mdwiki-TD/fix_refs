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
        $input = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից ։ <ref name="Os2018" />\n\n== test ==';
        $expected = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ<ref name="Os2018" /><ref name="Li2018" />։ Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում<ref name="Luc2021" />։ Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից <ref name="Os2018" />։\n\n== test ==';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy'));
    }
    public function testRemoveSpaceEnd()
    {
        $input = 'Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] կողմից <ref name="Os2018" /><ref>{{ref\n|<!!>\n}}</ref>։ կողմից <ref name="Os2018" /><ref>{{ref\n|<!!>\n}}</ref>։\n== test ==';

        $expected = 'Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] կողմից <ref name="Os2018" /><ref>{{ref\n|<!!>\n}}</ref>։ կողմից<ref name="Os2018" /><ref>{{ref\n|<!!>\n}}</ref>։\n== test ==';

        $this->assertEqualCompare($expected, $input, remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy'));
    }
}
