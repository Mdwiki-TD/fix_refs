<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\MovesDots\move_dots_before_refs;

class mv_dots_beforeTest extends MyFunctionTest
{

    public function _testMoveDotsBeforeAR()
    {
        $expected = 'Autism is a [[developmental disorder]] characterized by difficulties in social interaction and communication, and restricted and repetitive behavior<ref name="DSM5" />, Often notices any of their child\'s life . <ref name="Land2008">{{استشهاد بدورية محكمة|vauthors= Landa RJ |pmid= 18253102 | doi-access= free|عنوان=Diagnosis of autism spectrum disorders in the first 3 years of life|صحيفة=Nat Clin Pract Neurol|المجلد=4|العدد=3|صفحات=138–147|سنة=2008|دوي=10.1038/ncpneuro0731}}</ref><ref name="DSM5">{{استشهاد بكتاب|عنوان=Diagnostic and Statistical Manual of Mental Disorders, Fifth Edition|الفصل=Autism Spectrum Disorder, 299.00 (F84.0)|محرر=American Psychiatric Association|سنة=2013|ناشر=American Psychiatric Publishing}}</ref> These signs often develop gradually, and they suffer from: [[Autism spectrum]] In skills [[developmental milestones]] at a normal pace   <ref name="Stef2008">{{استشهاد بدورية محكمة| vauthors = Stefanatos GA | s2cid = 34658024 | pmid = 18956241 |عنوان=Regression in autistic spectrum disorders|صحيفة=Neuropsychol Rev|المجلد=18|العدد=4|صفحات=305–319|سنة=2008|دوي=10.1007/s11065-008-9073-y}}</ref>.';
        $input = 'Autism is a [[developmental disorder]] characterized by difficulties in social interaction and communication, and restricted and repetitive behavior, <ref name="DSM5" /> Often notices any of their child\'s life . <ref name="Land2008">{{استشهاد بدورية محكمة|vauthors= Landa RJ |pmid= 18253102 | doi-access= free|عنوان=Diagnosis of autism spectrum disorders in the first 3 years of life|صحيفة=Nat Clin Pract Neurol|المجلد=4|العدد=3|صفحات=138–147|سنة=2008|دوي=10.1038/ncpneuro0731}}</ref><ref name="DSM5">{{استشهاد بكتاب|عنوان=Diagnostic and Statistical Manual of Mental Disorders, Fifth Edition|الفصل=Autism Spectrum Disorder, 299.00 (F84.0)|محرر=American Psychiatric Association|سنة=2013|ناشر=American Psychiatric Publishing}}</ref> These signs often develop gradually, and they suffer from: [[Autism spectrum]] In skills [[developmental milestones]] at a normal pace. <ref name="Stef2008">{{استشهاد بدورية محكمة| vauthors = Stefanatos GA | s2cid = 34658024 | pmid = 18956241 |عنوان=Regression in autistic spectrum disorders|صحيفة=Neuropsychol Rev|المجلد=18|العدد=4|صفحات=305–319|سنة=2008|دوي=10.1007/s11065-008-9073-y}}</ref>';
        $this->assertEqualCompare($expected, $input, move_dots_before_refs($input, 'ar'));
    }
    public function testMoveDotsBeforeSingleDot()
    {
        $input = "This is a sentence<ref>Reference 1</ref>.";
        $expected = "This is a sentence. <ref>Reference 1</ref>";
        $this->assertEqualCompare($expected, $input, move_dots_before_refs($input, 'ar'));
    }

    public function testMoveDotsBeforeMultipleDots()
    {
        $input = "First sentence. Second sentence<ref>Reference 1</ref>.";
        $expected = "First sentence. Second sentence. <ref>Reference 1</ref>";
        $this->assertEqualCompare($expected, $input, move_dots_before_refs($input, 'ar'));
    }

    public function testMoveDotsBeforeMultipleRefs()
    {
        $input = "Text<ref>Ref1</ref><ref>Ref2</ref>..";
        $expected = "Text. <ref>Ref1</ref><ref>Ref2</ref>";
        $this->assertEqualCompare($expected, $input, move_dots_before_refs($input, 'ar'));
    }

    public function testMoveDotsBeforeDifferentPunctuation()
    {
        $input = "Text<ref>Reference</ref>,";
        $expected = "Text, <ref>Reference</ref>";
        $this->assertEqualCompare($expected, $input, move_dots_before_refs($input, 'ar'));
    }

    public function testMoveDotsBeforeAr2()
    {
        $input = "Text<ref>Reference</ref>، تجربة";
        $expected = "Text، <ref>Reference</ref> تجربة";
        $this->assertEqualCompare($expected, $input, move_dots_before_refs($input, 'ar'));
    }
}
