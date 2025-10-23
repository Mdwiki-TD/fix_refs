from __future__ import annotations

from __future__ import annotations

from src.helps_bots.remove_space import remove_spaces_between_ref_and_punctuation
from tests.conftest import assert_equal_compare


class remove_space2ExtraTest:
    def testRefNoPunctuationAfter(self) -> None:
        input_text = 'Sentence <ref name="X1" /> continues here'
        assert_equal_compare(input_text, input_text, remove_spaces_between_ref_and_punctuation(input_text))

    def testMultipleSpacesBeforePunctuation(self) -> None:
        input_text = 'Sentence <ref name="X2" />     .'
        expected = 'Sentence <ref name="X2" />.'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text))

    def testClosingRefDoublePunctuation(self) -> None:
        input_text = 'Sentence</ref> .:'
        expected = 'Sentence</ref>.:'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text))

    def testRefWithAttributes(self) -> None:
        input_text = 'Sentence <ref name="X3" group="note" /> .'
        expected = 'Sentence <ref name="X3" group="note" />.'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text))

    def testEmptyRefTag(self) -> None:
        input_text = 'Sentence<ref></ref> .'
        expected = 'Sentence<ref></ref>.'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text))

    def testAlreadyCorrectShouldStaySame(self) -> None:
        input_text = 'Sentence <ref name="X4" />.'
        assert_equal_compare(input_text, input_text, remove_spaces_between_ref_and_punctuation(input_text))

    def testMixOfColonTypes(self) -> None:
        input_text = 'Sentence <ref name="X5" /> : Another<ref name="X6" /> ：'
        expected = 'Sentence <ref name="X5" />: Another<ref name="X6" /> ：'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text))

    def testMalformedNestedRefs(self) -> None:
        input_text = 'Sentence <ref><ref /> 。'
        expected = 'Sentence <ref><ref />。'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text))


class remove_space2Test(remove_space2ExtraTest):
    def testRemoveSpaceEnd1(self) -> None:
        input_text = (
            'Բուժումը ներառում է [[Թերապիա (բուժում)|օժանդակ միջոցառումներ]], ինչպիսիք են գանգի պաշտպանիչ սարքը և ատամնաբուժական '
            'խնամքը <ref name="NORD2004" /> : Ոսկրային որոշակի անոմալիաներ շտկելու համար կարող է իրականացվել վիրահատություն '
            '<ref name="GARD2016" /> : Կյանքի տևողությունը, ընդհանուր առմամբ, նորմալ է<ref name="Yo2002">{{Cite book|last=Young|first='
            'Ian D.|title=Genetics for Orthopedic Surgeons: The Molecular Genetic Basis of Orthopedic Disorders|date=2002|publisher='
            'Remedica|isbn=9781901346428|page=92|url=https://books.google.com/books?id=QyVsI5b2zJoC&pg=PT52|language=en|url-status='
            'live|archive-url=https://web.archive.org/web/20161103235838/https://books.google.ca/books?id=QyVsI5b2zJoC&pg=PT52|archive-date='
            '2016-11-03}}</ref>։'
        )
        expected = (
            'Բուժումը ներառում է [[Թերապիա (բուժում)|օժանդակ միջոցառումներ]], ինչպիսիք են գանգի պաշտպանիչ սարքը և ատամնաբուժական '
            'խնամքը <ref name="NORD2004" />: Ոսկրային որոշակի անոմալիաներ շտկելու համար կարող է իրականացվել վիրահատություն '
            '<ref name="GARD2016" />: Կյանքի տևողությունը, ընդհանուր առմամբ, նորմալ '
            '<ref name="Yo2002">{{Cite book|last=Young|first=Ian D.|title=Genetics for Orthopedic Surgeons: The Molecular Genetic '
            'Basis of Orthopedic Disorders|date=2002|publisher=Remedica|isbn=9781901346428|page=92|url=https://books.google.com/books?id=QyVsI5b2zJoC&pg=PT52|language=en|url-status='
            'live|archive-url=https://web.archive.org/web/20161103235838/https://books.google.ca/books?id=QyVsI5b2zJoC&pg=PT52|archive-date='
            '2016-11-03}}</ref>։'
        )
        assert_equal_compare(
            expected,
            input_text,
            remove_spaces_between_ref_and_punctuation(input_text, 'hy'),
        )

    def testRemoveSpaceArmenian(self) -> None:
        input_text = 'Տեքստ <ref name="NORD2004" /> ։'
        expected = 'Տեքստ <ref name="NORD2004" />։'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'hy'))

    def testRemoveSpaceHindi(self) -> None:
        input_text = 'पाठ <ref name="IND2001" /> ।'
        expected = 'पाठ <ref name="IND2001" />।'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'hi'))

    def testRemoveSpaceChinese(self) -> None:
        input_text = '文本 <ref name="CN2003" /> 。'
        expected = '文本 <ref name="CN2003" />。'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'zh'))

    def testRemoveSpaceEnglish(self) -> None:
        input_text = 'Text <ref name="EN2005" /> .'
        expected = 'Text <ref name="EN2005" />.'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'en'))

    def testRemoveSpaceColon(self) -> None:
        input_text = 'Sentence <ref name="TEST" /> :'
        expected = 'Sentence <ref name="TEST" />:'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'en'))

    def testRemoveSpaceClosingRefArmenian(self) -> None:
        input_text = 'Տեքստ</ref> ։'
        expected = 'Տեքստ</ref>։'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'hy'))

    def testRemoveSpaceClosingRefChinese(self) -> None:
        input_text = '文本</ref> 。'
        expected = '文本</ref>。'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'zh'))

    def testRemoveSpaceClosingRefEnglish(self) -> None:
        input_text = 'Text</ref> .'
        expected = 'Text</ref>.'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'en'))

    def testRemoveSpaceClosingRefColon(self) -> None:
        input_text = 'Sentence</ref> :'
        expected = 'Sentence</ref>:'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'en'))

    def testNoSpaceShouldStaySame(self) -> None:
        input_text = 'Text<ref name="OK" />.'
        assert_equal_compare(input_text, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'en'))

    def testMultipleRefsSameLineArmenian(self) -> None:
        input_text = 'Նախադասություն <ref name="N1" /> ։ Հաջորդը <ref name="N2" /> :'
        expected = 'Նախադասություն <ref name="N1" />։ Հաջորդը <ref name="N2" />:'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'hy'))

    def testMultipleRefsSameLineChinese(self) -> None:
        input_text = '句子 <ref name="C1" /> 。 然后 <ref name="C2" /> :'
        expected = '句子 <ref name="C1" />。 然后 <ref name="C2" />:'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'zh'))

    def testClosingRefMultipleMarks(self) -> None:
        input_text = 'Text</ref> ։ More text</ref> . And again</ref> :'
        expected = 'Text</ref>։ More text</ref>. And again</ref>:'
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'en'))

    def testLongMixedParagraph(self) -> None:
        input_text = (
            'Բուժումը ներառում է [[Therapy|թերապիա]] <ref name="NORD2004" /> ։ Կյանքի տևողությունը նորմալ է<ref name="Yo2002">{{Cite '
            'book}}</ref> ։ النص العربي <ref name="AR2020" /> : والنهاية طبيعية<ref name="AR2021" /> . Chinese 文本<ref name="CN1" /> 。 '
            'Final<ref name="EN1" /> :'
        )
        expected = (
            'Բուժումը ներառում է [[Therapy|թերապիա]] <ref name="NORD2004" />։ Կյանքի տևողությունը նորմալ է<ref name="Yo2002">{{Cite '
            'book}}</ref>։ النص العربي <ref name="AR2020" />: والنهاية طبيعية<ref name="AR2021" />. Chinese 文本<ref name="CN1" />。 '
            'Final<ref name="EN1" />:'
        )
        assert_equal_compare(expected, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'multi'))

    def testNoSpacesRemainUnchanged(self) -> None:
        input_text = 'Sentence<ref name="OK" />: Another</ref>. End</ref>։'
        assert_equal_compare(input_text, input_text, remove_spaces_between_ref_and_punctuation(input_text, 'multi'))
