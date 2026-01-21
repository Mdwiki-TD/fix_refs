"""Tests for Spanish months (es_monthsTest.php)

Converted from tests/es_bots/es_monthsTest.php
"""
import pytest
from src.bots.months import make_date_new_val_es
from src.lang_bots.es_helpers import fix_es_months_in_refs, fix_es_months_in_texts


class TestEsMonths:
    """Test cases for Spanish month translation"""

    def test_make_date_new_val_es_with_full_date(self):
        """Test converting full date with month to Spanish"""
        assert make_date_new_val_es("July 25, 1975") == "25 de julio de 1975"

    def test_temp_in_wikitexts(self):
        """Test fixing months in references within wikitext"""
        input_text = ('test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}}</ref>\n'
                      '<ref name="AHFS2016">{{Citar web|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 December 2016}}</ref>')

        expected = ('test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 de diciembre de 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de diciembre de 2016}}</ref>\n'
                    '<ref name="AHFS2016">{{Citar web|acessodata=8 de diciembre de 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de diciembre de 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 de diciembre de 2016}}</ref>')

        result = fix_es_months_in_refs(input_text)
        assert result == expected

    def test_temp_in_ref(self):
        """Test fixing months in single reference"""
        input_text = '<ref name="test" group="notes">{{cite web|date=25  December 2016 |}}</ref>'
        expected = '<ref name="test" group="notes">{{cite web|date=25 de diciembre de 2016|}}</ref>'
        result = fix_es_months_in_refs(input_text)
        assert result == expected

    def test_temp_in_templates(self):
        """Test fixing months in template"""
        input_text = '{{cite web|date=10 January, 2023|}}'
        expected = '{{cite web|date=10 de enero de 2023|}}'
        result = fix_es_months_in_texts(input_text)
        assert result == expected

    def test_temp_in_templates_more(self):
        """Test fixing months in multiple templates"""
        input_text = '{{cite web|date=10 January, 2023|}} {{cite book|time = test|date = 10 de enero de 2023 }}'
        expected = '{{cite web|date=10 de enero de 2023|}} {{cite book|time=test|date=10 de enero de 2023}}'
        result = fix_es_months_in_texts(input_text)
        assert result == expected
