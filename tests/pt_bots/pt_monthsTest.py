from __future__ import annotations

from src.pt_bots.fix_pt_months import (
    fix_pt_months_in_refs,
    fix_pt_months_in_texts,
    rm_ref_spaces,
)
from tests.conftest import assert_equal_compare


class pt_monthsTest:
    def testTempInWikiTexts(self) -> None:
        input_text = (
            'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|'
            'publicado=The American Society of Health-System Pharmacists|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}}</ref>'
            '<ref name="AHFS2016">{{Citar web|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}} xxxxxxxxxxxxxxxx '
            '{{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 December 2016}}</ref>'
        )
        expected = (
            'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|'
            'publicado=The American Society of Health-System Pharmacists|acessodata=8 de dezembro 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de dezembro 2016}}</ref>'
            '<ref name="AHFS2016">{{Citar web|acessodata=8 de dezembro 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de dezembro 2016}} xxxxxxxxxxxxxxxx '
            '{{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 de dezembro 2016}}</ref>'
        )
        assert_equal_compare(expected, input_text, fix_pt_months_in_refs(input_text))

    def testTempInRef(self) -> None:
        input_text = '<ref name="test" group="notes">{{cite web|date=25  December 2016 |}}</ref>'
        expected = '<ref name="test" group="notes">{{cite web|date=25 de dezembro 2016|}}</ref>'
        assert_equal_compare(expected, input_text, fix_pt_months_in_refs(input_text))

    def testTempInTemplates(self) -> None:
        input_text = '{{cite web|date=10 January, 2023|}}'
        expected = '{{cite web|date=10 de janeiro 2023|}}'
        assert_equal_compare(expected, input_text, fix_pt_months_in_texts(input_text))

    def testTempInTemplatesMore(self) -> None:
        input_text = '{{cite web|date=10 January, 2023|}} {{cite book|time = test|date = 10 de janeiro 2023 }}'
        expected = '{{cite web|date=10 de janeiro 2023|}} {{cite book|time=test|date=10 de janeiro 2023}}'
        assert_equal_compare(expected, input_text, fix_pt_months_in_texts(input_text))

    def testRemoveSpacesAfterDotBeforeRef(self) -> None:
        input_text = "This is a sentence . <ref>Reference</ref>"
        expected = "This is a sentence.<ref>Reference</ref>"
        assert_equal_compare(expected, input_text, rm_ref_spaces(input_text))

    def testRemoveSpacesAfterCommaBeforeRef(self) -> None:
        input_text = "Hello , <ref>Ref</ref>"
        expected = "Hello,<ref>Ref</ref>"
        assert_equal_compare(expected, input_text, rm_ref_spaces(input_text))

    def testMultipleRefsTogether(self) -> None:
        input_text = "Sentence . <ref>First</ref> <ref>Second</ref>"
        expected = "Sentence.<ref>First</ref> <ref>Second</ref>"
        assert_equal_compare(expected, input_text, rm_ref_spaces(input_text))

    def testNoSpacesShouldRemainUnchanged(self) -> None:
        input_text = "Correct.<ref>Already OK</ref>"
        assert_equal_compare(input_text, input_text, rm_ref_spaces(input_text))

    def testNoRefNoChange(self) -> None:
        input_text = "This is a sentence . Without ref."
        assert_equal_compare(input_text, input_text, rm_ref_spaces(input_text))
