"""
Test mini_fixes_bot module

Matches tests from mini_fixes_botTest.php
"""

import pytest
from tests.bootstrap import MyFunctionTest
from src.bots.mini_fixes_bot import (
    mini_fixes,
    fix_sections_titles,
    refs_tags_spaces,
    mini_fixes_after_fixing
)


class TestMiniFixes(MyFunctionTest):
    """Tests for mini fixes bot"""
    
    def test_refs_tags_spaces(self):
        """Test removing spaces between ref tags"""
        input_text = "</ref> <ref name='test'>"
        expected = "</ref><ref name='test'>"
        result = refs_tags_spaces(input_text)
        assert expected == result
    
    def test_refs_tags_spaces_multiple(self):
        """Test multiple ref tag spaces"""
        input_text = "<ref name='A' /> <ref name='B'>"
        expected = "<ref name='A' /><ref name='B'>"
        result = refs_tags_spaces(input_text)
        assert expected == result
    
    def test_fix_sections_titles_hr(self):
        """Test Croatian section title fix"""
        input_text = "== Reference =="
        expected = "== Izvori =="
        result = fix_sections_titles(input_text, "hr")
        assert expected == result
    
    def test_fix_sections_titles_sw(self):
        """Test Swahili section title fix"""
        input_text = "== Marejeleo =="
        expected = "== Marejeo =="
        result = fix_sections_titles(input_text, "sw")
        assert expected == result
    
    def test_fix_sections_titles_ru(self):
        """Test Russian section title fix"""
        input_text = "== Ссылки =="
        expected = "== Примечания =="
        result = fix_sections_titles(input_text, "ru")
        assert expected == result
    
    def test_mini_fixes_combined(self):
        """Test combined mini fixes"""
        input_text = "</ref> <ref name='test'> text"
        result = mini_fixes(input_text, "en")
        expected = "</ref><ref name='test'> text"
        assert expected == result
