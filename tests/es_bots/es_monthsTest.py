from __future__ import annotations

from src.bots.months_new_value import make_date_new_val_es
from src.es_bots.es_months import fix_es_months_in_refs, fix_es_months_in_texts
from tests.conftest import assert_equal_compare


class es_monthsTest:
    def test_make_date_new_val_es_with_full_date(self) -> None:
        assert make_date_new_val_es("July 25, 1975") == "25 de julio de 1975"

    def testTempInWikiTexts(self) -> None:
        input_text = (
            'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|'
            'publicado=The American Society of Health-System Pharmacists|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}}</ref>'
            '<ref name="AHFS2016">{{Citar web|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}} xxxxxxxxxxxxxxxx '
            '{{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 December 2016}}</ref>'
        )
        expected = (
            'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|'
            'publicado=The American Society of Health-System Pharmacists|acessodata=8 de diciembre de 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de diciembre de 2016}}</ref>'
            '<ref name="AHFS2016">{{Citar web|acessodata=8 de diciembre de 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de diciembre de 2016}} xxxxxxxxxxxxxxxx '
            '{{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 de diciembre de 2016}}</ref>'
        )
        assert_equal_compare(expected, input_text, fix_es_months_in_refs(input_text))

    def testTempInRef(self) -> None:
        input_text = '<ref name="test" group="notes">{{cite web|date=25  December 2016 |}}</ref>'
        expected = '<ref name="test" group="notes">{{cite web|date=25 de diciembre de 2016|}}</ref>'
        assert_equal_compare(expected, input_text, fix_es_months_in_refs(input_text))

    def testTempInTemplates(self) -> None:
        input_text = '{{cite web|date=10 January, 2023|}}'
        expected = '{{cite web|date=10 de enero de 2023|}}'
        assert_equal_compare(expected, input_text, fix_es_months_in_texts(input_text))

    def testTempInTemplatesMore(self) -> None:
        input_text = '{{cite web|date=10 January, 2023|}} {{cite book|time = test|date = 10 de enero de 2023 }}'
        expected = '{{cite web|date=10 de enero de 2023|}} {{cite book|time=test|date=10 de enero de 2023}}'
        assert_equal_compare(expected, input_text, fix_es_months_in_texts(input_text))
