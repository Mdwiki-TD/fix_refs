<?php


use FixRefs\Tests\MyFunctionTest;
use function WpRefs\RemoveSpace\remove_spaces_between_ref_and_punctuation;

class remove_space2ExtraTest extends MyFunctionTest
{
    public function testRefNoPunctuationAfter()
    {
        $input = 'Sentence <ref name="X1" /> continues here';
        $expected = 'Sentence <ref name="X1" /> continues here';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }

    public function testMultipleSpacesBeforePunctuation()
    {
        $input = 'Sentence <ref name="X2" />     .';
        $expected = 'Sentence <ref name="X2" />.';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }

    public function testClosingRefDoublePunctuation()
    {
        $input = 'Sentence</ref> .:';
        $expected = 'Sentence</ref>.:'; // space removed only before the first dot
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }

    public function testRefWithAttributes()
    {
        $input = 'Sentence <ref name="X3" group="note" /> .';
        $expected = 'Sentence <ref name="X3" group="note" />.';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }

    public function testEmptyRefTag()
    {
        $input = 'Sentence<ref></ref> .';
        $expected = 'Sentence<ref></ref>.';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }

    public function testAlreadyCorrectShouldStaySame()
    {
        $input = 'Sentence <ref name="X4" />.';
        $expected = 'Sentence <ref name="X4" />.';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }

    public function testMixOfColonTypes()
    {
        $input = 'Sentence <ref name="X5" /> : Another<ref name="X6" /> ：';
        $expected = 'Sentence <ref name="X5" />: Another<ref name="X6" /> ：';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }

    public function testMalformedNestedRefs()
    {
        $input = 'Sentence <ref><ref /> 。';
        $expected = 'Sentence <ref><ref />。';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input));
    }
}

class remove_space2Test extends remove_space2ExtraTest
{

    public function testRemoveSpaceEnd1()
    {
        $input = 'Բուժումը ներառում է [[Թերապիա (բուժում)|օժանդակ միջոցառումներ]], ինչպիսիք են գանգի պաշտպանիչ սարքը և ատամնաբուժական խնամքը <ref name="NORD2004" /> : Ոսկրային որոշակի անոմալիաներ շտկելու համար կարող է իրականացվել վիրահատություն <ref name="GARD2016" /> : Կյանքի տևողությունը, ընդհանուր առմամբ, նորմալ է<ref name="Yo2002">{{Cite book|last=Young|first=Ian D.|title=Genetics for Orthopedic Surgeons: The Molecular Genetic Basis of Orthopedic Disorders|date=2002|publisher=Remedica|isbn=9781901346428|page=92|url=https://books.google.com/books?id=QyVsI5b2zJoC&pg=PT52|language=en|url-status=live|archive-url=https://web.archive.org/web/20161103235838/https://books.google.ca/books?id=QyVsI5b2zJoC&pg=PT52|archive-date=2016-11-03}}</ref>։';
        // ---
        $expected = 'Բուժումը ներառում է [[Թերապիա (բուժում)|օժանդակ միջոցառումներ]], ինչպիսիք են գանգի պաշտպանիչ սարքը և ատամնաբուժական խնամքը <ref name="NORD2004" />: Ոսկրային որոշակի անոմալիաներ շտկելու համար կարող է իրականացվել վիրահատություն <ref name="GARD2016" />: Կյանքի տևողությունը, ընդհանուր առմամբ, նորմալ է<ref name="Yo2002">{{Cite book|last=Young|first=Ian D.|title=Genetics for Orthopedic Surgeons: The Molecular Genetic Basis of Orthopedic Disorders|date=2002|publisher=Remedica|isbn=9781901346428|page=92|url=https://books.google.com/books?id=QyVsI5b2zJoC&pg=PT52|language=en|url-status=live|archive-url=https://web.archive.org/web/20161103235838/https://books.google.ca/books?id=QyVsI5b2zJoC&pg=PT52|archive-date=2016-11-03}}</ref>։';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'hy'));
    }
    public function testRemoveSpaceArmenian()
    {
        $input = 'Տեքստ <ref name="NORD2004" /> ։';
        $expected = 'Տեքստ <ref name="NORD2004" />։';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'hy'));
    }

    public function testRemoveSpaceHindi()
    {
        $input = 'पाठ <ref name="IND2001" /> ।';
        $expected = 'पाठ <ref name="IND2001" />।';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'hi'));
    }

    public function testRemoveSpaceChinese()
    {
        $input = '文本 <ref name="CN2003" /> 。';
        $expected = '文本 <ref name="CN2003" />。';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'zh'));
    }

    public function testRemoveSpaceEnglish()
    {
        $input = 'Text <ref name="EN2005" /> .';
        $expected = 'Text <ref name="EN2005" />.';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'en'));
    }

    public function testRemoveSpaceColon()
    {
        $input = 'Sentence <ref name="TEST" /> :';
        $expected = 'Sentence <ref name="TEST" />:';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'en'));
    }

    public function testRemoveSpaceClosingRefArmenian()
    {
        $input = 'Տեքստ</ref> ։';
        $expected = 'Տեքստ</ref>։';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'hy'));
    }

    public function testRemoveSpaceClosingRefChinese()
    {
        $input = '文本</ref> 。';
        $expected = '文本</ref>。';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'zh'));
    }

    public function testRemoveSpaceClosingRefEnglish()
    {
        $input = 'Text</ref> .';
        $expected = 'Text</ref>.';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'en'));
    }

    public function testRemoveSpaceClosingRefColon()
    {
        $input = 'Sentence</ref> :';
        $expected = 'Sentence</ref>:';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'en'));
    }

    public function testNoSpaceShouldStaySame()
    {
        $input = 'Text<ref name="OK" />.';
        $expected = 'Text<ref name="OK" />.';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'en'));
    }
    // === حالات بسيطة كما سبق (مغطاة بالفعل) === //

    public function testMultipleRefsSameLineArmenian()
    {
        $input = 'Նախադասություն <ref name="N1" /> ։ Հաջորդը <ref name="N2" /> :';
        $expected = 'Նախադասություն <ref name="N1" />։ Հաջորդը <ref name="N2" />:';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'hy'));
    }

    public function testMultipleRefsSameLineChinese()
    {
        $input = '句子 <ref name="C1" /> 。 然后 <ref name="C2" /> :';
        $expected = '句子 <ref name="C1" />。 然后 <ref name="C2" />:';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'zh'));
    }

    public function testClosingRefMultipleMarks()
    {
        $input = 'Text</ref> ։ More text</ref> . And again</ref> :';
        $expected = 'Text</ref>։ More text</ref>. And again</ref>:';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'en'));
    }

    public function testLongMixedParagraph()
    {
        $input = 'Բուժումը ներառում է [[Therapy|թերապիա]] <ref name="NORD2004" /> ։ Կյանքի տևողությունը նորմալ է<ref name="Yo2002">{{Cite book}}</ref> ։ ' .
            'النص العربي <ref name="AR2020" /> : والنهاية طبيعية<ref name="AR2021" /> . ' .
            'Chinese 文本<ref name="CN1" /> 。 Final<ref name="EN1" /> :';

        $expected = 'Բուժումը ներառում է [[Therapy|թերապիա]] <ref name="NORD2004" />։ Կյանքի տևողությունը նորմալ է<ref name="Yo2002">{{Cite book}}</ref>։ ' .
            'النص العربي <ref name="AR2020" />: والنهاية طبيعية<ref name="AR2021" />. ' .
            'Chinese 文本<ref name="CN1" />。 Final<ref name="EN1" />:';

        $this->assertEqualCompare($expected, $input, remove_spaces_between_ref_and_punctuation($input, 'multi'));
    }

    public function testNoSpacesRemainUnchanged()
    {
        $input = 'Sentence<ref name="OK" />: Another</ref>. End</ref>։';
        $this->assertEqualCompare($input, $input, remove_spaces_between_ref_and_punctuation($input, 'multi'));
    }
}
