<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;
use function WpRefs\MovesDots\move_dots_after_refs;
use function WpRefs\MovesDots\move_dots_before_refs;

class mv_dotsTest extends TestCase
{
    // Tests for move_dots_after_refs function
    public function testMoveDotsTextSingleDot()
    {
        $input = "This is a sentence。<ref>Reference 1</ref>";
        $expected = "This is a sentence<ref>Reference 1</ref>。";
        $this->assertEquals($expected, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsTextMultipleDots()
    {
        $input = "First sentence. Second sentence.<ref>Reference 1</ref>";
        $expected = "First sentence. Second sentence<ref>Reference 1</ref>.";
        $this->assertEquals($expected, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsTextMultipleRefs()
    {
        $input = "Text।<ref>Ref1</ref><ref>Ref2</ref>";
        $expected = "Text<ref>Ref1</ref><ref>Ref2</ref>।";
        $this->assertEquals($expected, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsTextNoDot()
    {
        $input = "Text<ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>";
        $this->assertEquals($expected, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsTextDifferentPunctuation()
    {
        $input = "Text, <ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>,";
        $this->assertEquals($expected, move_dots_after_refs($input, 'en'));
    }
    public function testMoveDotsTextAR()
    {
        $input = 'التوحد هو [[developmental disorder|اضطراب في النمو]] يتميز بصعوبات في التفاعل الاجتماعي والتواصل، وسلوك مقيد ومتكرر<ref name="DSM5" />، غالبًا ما يلاحظ اى من حياة طفلهم . <ref name="Land2008">{{استشهاد بدورية محكمة|vauthors= Landa RJ |pmid= 18253102 | doi-access= free|عنوان=Diagnosis of autism spectrum disorders in the first 3 years of life|صحيفة=Nat Clin Pract Neurol|المجلد=4|العدد=3|صفحات=138–147|سنة=2008|دوي=10.1038/ncpneuro0731}}</ref><ref name="DSM5">{{استشهاد بكتاب|عنوان=Diagnostic and Statistical Manual of Mental Disorders, Fifth Edition|الفصل=Autism Spectrum Disorder, 299.00 (F84.0)|محرر=American Psychiatric Association|سنة=2013|ناشر=American Psychiatric Publishing}}</ref> غالبًا ما تتطور هذه العلامات تدريجيًا، يعانون من [[Autism spectrum|تدهور]] في مهارات [[developmental milestones|مراحل النمو]] بوتيرة طبيعية .  <ref name="Stef2008">{{استشهاد بدورية محكمة| vauthors = Stefanatos GA | s2cid = 34658024 | pmid = 18956241 |عنوان=Regression in autistic spectrum disorders|صحيفة=Neuropsychol Rev|المجلد=18|العدد=4|صفحات=305–319|سنة=2008|دوي=10.1007/s11065-008-9073-y}}</ref>';
        $expected = 'التوحد هو [[developmental disorder|اضطراب في النمو]] يتميز بصعوبات في التفاعل الاجتماعي والتواصل، وسلوك مقيد ومتكرر<ref name="DSM5" />، غالبًا ما يلاحظ اى من حياة طفلهم . <ref name="Land2008">{{استشهاد بدورية محكمة|vauthors= Landa RJ |pmid= 18253102 | doi-access= free|عنوان=Diagnosis of autism spectrum disorders in the first 3 years of life|صحيفة=Nat Clin Pract Neurol|المجلد=4|العدد=3|صفحات=138–147|سنة=2008|دوي=10.1038/ncpneuro0731}}</ref><ref name="DSM5">{{استشهاد بكتاب|عنوان=Diagnostic and Statistical Manual of Mental Disorders, Fifth Edition|الفصل=Autism Spectrum Disorder, 299.00 (F84.0)|محرر=American Psychiatric Association|سنة=2013|ناشر=American Psychiatric Publishing}}</ref> غالبًا ما تتطور هذه العلامات تدريجيًا، يعانون من [[Autism spectrum|تدهور]] في مهارات [[developmental milestones|مراحل النمو]] بوتيرة طبيعية .  <ref name="Stef2008">{{استشهاد بدورية محكمة| vauthors = Stefanatos GA | s2cid = 34658024 | pmid = 18956241 |عنوان=Regression in autistic spectrum disorders|صحيفة=Neuropsychol Rev|المجلد=18|العدد=4|صفحات=305–319|سنة=2008|دوي=10.1007/s11065-008-9073-y}}</ref>';
        $this->assertEquals($expected, move_dots_before_refs($input, 'ar'));
    }
    public function testMoveDotsTextHy()
    {
        $input = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից ։ <ref name="Os2018" />';
        $expected = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ<ref name="Os2018" /><ref name="Li2018" />։ Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում<ref name="Luc2021" />։ Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից<ref name="Os2018" />։';
        $this->assertEquals($expected, move_dots_after_refs($input, 'hy'));
    }
}
