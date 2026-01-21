"""Tests for adding English language parameter (add_lang_en.py)

Converted from tests/en_lang_paramTest.php
"""
import pytest
from src.bots.add_lang_en import add_lang_en_to_refs


class TestAddLangEn:
    """Test cases for adding English language parameter to references"""

    def test_add_lang_en_simple_ref(self):
        """Test adding language=en to simple reference"""
        input_text = "<ref>{{Citar web|Some text}}</ref> {{temp|test=1}}"
        expected = "<ref>{{Citar web|Some text|language=en}}</ref> {{temp|test=1}}"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_en_existing_language(self):
        """Test not modifying existing language parameter"""
        input_text = "<ref>{{Citar web|Text|language=fr}}</ref>"
        expected = "<ref>{{Citar web|Text|language=fr}}</ref>"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_en_empty_ref(self):
        """Test handling empty reference"""
        input_text = "<ref></ref>"
        expected = "<ref></ref>"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_en_with_existing_params(self):
        """Test adding language with existing parameters"""
        input_text = " {{temp|test=1}} <ref>{{Citar web|Text|author=John}}</ref>"
        expected = " {{temp|test=1}} <ref>{{Citar web|Text|author=John|language=en}}</ref>"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_malformed_ref(self):
        """Test fixing malformed language parameter"""
        input_text = "<ref>{{Citar web|Text|language = }}</ref> {{temp|test=1}}"
        expected = "<ref>{{Citar web|Text|language=en}}</ref> {{temp|test=1}}"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_en_arabic(self):
        """Test not overwriting Arabic language"""
        input_text = "<ref>{{Citar web|Text|language=ar}}</ref>"
        expected = "<ref>{{Citar web|Text|language=ar}}</ref>"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_en_no_change_needed(self):
        """Test reference already has language=en"""
        input_text = " {{temp|test=1}} <ref>{{Citar web|Text|language=en}}</ref>"
        expected = " {{temp|test=1}} <ref>{{Citar web|Text|language=en}}</ref>"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_multiple_refs(self):
        """Test adding language to multiple references"""
        input_text = "<ref>{{Cite web|text1}}</ref> Some text <ref>{{Cite web|text2}}</ref>"
        expected = "<ref>{{Cite web|text1|language=en}}</ref> Some text <ref>{{Cite web|text2|language=en}}</ref>"
        result = add_lang_en_to_refs(input_text)
        assert result == expected

    def test_add_lang_mixed_refs(self):
        """Test mixed references with and without language"""
        input_text = "<ref>{{Cite|no lang}}</ref> <ref>{{Cite|language=es}}</ref> <ref>{{Cite|another}}</ref>"
        expected = "<ref>{{Cite|no lang|language=en}}</ref> <ref>{{Cite|language=es}}</ref> <ref>{{Cite|another|language=en}}</ref>"
        result = add_lang_en_to_refs(input_text)
        assert result == expected
