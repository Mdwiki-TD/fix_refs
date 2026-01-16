"""
Test for helps_bots.en_lang_param module

Ported from: tests/en_lang_paramTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.helps_bots.en_lang_param import add_lang_en_to_refs


class TestEnLangParam(MyFunctionTest):
    """Test adding English language parameter to refs"""

    def test_add_lang_en_basic(self):
        """Test adding language=en parameter"""
        text = "{{cite web|title=Test}}"
        result = add_lang_en_to_refs(text)
        assert "language" in result.lower()

    def test_add_lang_en_with_existing(self):
        """Test with existing language parameter"""
        text = "{{cite web|title=Test|language=fr}}"
        result = add_lang_en_to_refs(text)
        # Should not change existing language
        assert "fr" in result
