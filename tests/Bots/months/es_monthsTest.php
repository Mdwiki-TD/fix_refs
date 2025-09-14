<?php



use PHPUnit\Framework\TestCase;

use function WpRefs\Bots\MonthNewValue\make_date_new_val_es;
use function WpRefs\Bots\es_months\fix_es_months_in_texts;
use function WpRefs\Bots\es_months\fix_es_months_in_refs;

class es_monthsTest extends TestCase
{
    public function test_make_date_new_val_es_with_full_date()
    {
        $this->assertEquals("25 de julio de 1975", make_date_new_val_es("July 25, 1975"));
    }
    private function assertEqualCompare(string $expected, string $input, string $result)
    {
        if ($result === $input && $result !== $expected) {
            $this->fail("No changes were made! The function returned the input unchanged:\n$result");
        } else {
            $this->assertEquals($expected, $result, "Unexpected result:\n$result");
        }
    }
    public function testTempInWikiTexts()
    {
        $input = 'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}}</ref>
<ref name="AHFS2016">{{Citar web|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 December 2016}}</ref>';
        $expected = 'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 de diciembre de 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de diciembre de 2016}}</ref>
<ref name="AHFS2016">{{Citar web|acessodata=8 de diciembre de 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de diciembre de 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 de diciembre de 2016}}</ref>';
        $result = fix_es_months_in_refs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testTempInRef()
    {
        // ("25 de diciembre de 2016", make_date_new_val_pt("25 December 2016")
        $input = '<ref name="test" group="notes">{{cite web|date=25  December 2016 |}}</ref>';
        $expected = '<ref name="test" group="notes">{{cite web|date=25 de diciembre de 2016|}}</ref>';
        $result = fix_es_months_in_refs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testTempInTemplates()
    {
        $input = '{{cite web|date=10 January, 2023|}}';
        $expected = '{{cite web|date=10 de enero de 2023|}}';
        $result = fix_es_months_in_texts($input);
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testTempInTemplatesMore()
    {
        $input = '{{cite web|date=10 January, 2023|}} {{cite book|time = test|date = 10 de enero de 2023 }}';
        $expected = '{{cite web|date=10 de enero de 2023|}} {{cite book|time=test|date=10 de enero de 2023}}';
        $result = fix_es_months_in_texts($input);
        $this->assertEqualCompare($expected, $input, $result);
    }
}
