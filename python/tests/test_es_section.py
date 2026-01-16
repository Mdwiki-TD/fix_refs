"""
Test for es_bots.section module (Spanish section)

Ported from: tests/es_bots/esSectionTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.es_bots.section import es_section


class TestEsSection(MyFunctionTest):
    """Test Spanish section translation"""

    def test_es_section_basic(self):
        """Test basic Spanish section"""
        text = "Article text"
        lang = "es"
        sourcetitle = "Test"
        result = es_section(text, lang, sourcetitle)
        assert isinstance(result, str)
