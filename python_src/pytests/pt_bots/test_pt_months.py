"""Tests for Portuguese months (pt_monthsTest.php)

Converted from tests/pt_bots/pt_monthsTest.php
"""
import pytest
from src.lang_bots.pt_bot import rm_ref_spaces, fix_pt_months_in_texts, fix_pt_months_in_refs


class TestPtMonths:
    """Test cases for Portuguese month translation"""

    def test_temp_in_wikitexts(self):
        """Test fixing months in references within wikitext"""
        input_text = ('test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}}</ref>\n'
                      '<ref name="AHFS2016">{{Citar web|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 December 2016}}</ref>')

        expected = ('test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 de dezembro 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de dezembro 2016}}</ref>\n'
                    '<ref name="AHFS2016">{{Citar web|acessodata=8 de dezembro 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de dezembro 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 de dezembro 2016}}</ref>')

        result = fix_pt_months_in_refs(input_text)
        assert result == expected

    def test_temp_in_ref(self):
        """Test fixing months in single reference"""
        input_text = '<ref name="test" group="notes">{{cite web|date=25  December 2016 |}}</ref>'
        expected = '<ref name="test" group="notes">{{cite web|date=25 de dezembro 2016|}}</ref>'
        result = fix_pt_months_in_refs(input_text)
        assert result == expected

    def test_temp_in_templates(self):
        """Test fixing months in template"""
        input_text = '{{cite web|date=10 January, 2023|}}'
        expected = '{{cite web|date=10 de janeiro 2023|}}'
        result = fix_pt_months_in_texts(input_text)
        assert result == expected

    def test_temp_in_templates_more(self):
        """Test fixing months in multiple templates"""
        input_text = '{{cite web|date=10 January, 2023|}} {{cite book|time = test|date = 10 de janeiro 2023 }}'
        expected = '{{cite web|date=10 de janeiro 2023|}} {{cite book|time=test|date=10 de janeiro 2023}}'
        result = fix_pt_months_in_texts(input_text)
        assert result == expected

    def test_remove_spaces_after_dot_before_ref(self):
        """Test removing spaces after dot before ref"""
        input_text = "This is a sentence . <ref>Reference</ref>"
        expected = "This is a sentence.<ref>Reference</ref>"
        assert rm_ref_spaces(input_text) == expected

    def test_remove_spaces_after_comma_before_ref(self):
        """Test removing spaces after comma before ref"""
        input_text = "Hello , <ref>Ref</ref>"
        expected = "Hello,<ref>Ref</ref>"
        assert rm_ref_spaces(input_text) == expected

    def test_multiple_refs_together(self):
        """Test removing spaces with multiple refs together"""
        input_text = "Sentence . <ref>First</ref> <ref>Second</ref>"
        expected = "Sentence.<ref>First</ref> <ref>Second</ref>"
        assert rm_ref_spaces(input_text) == expected

    def test_no_spaces_should_remain_unchanged(self):
        """Test that text without spaces remains unchanged"""
        input_text = "Correct.<ref>Already OK</ref>"
        expected = "Correct.<ref>Already OK</ref>"
        assert rm_ref_spaces(input_text) == expected

    def test_no_ref_no_change(self):
        """Test that text without ref is unchanged"""
        input_text = "This is a sentence . Without ref."
        # Text without ref should remain unchanged
        assert rm_ref_spaces(input_text) == input_text
