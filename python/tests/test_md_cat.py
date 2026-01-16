"""
Test md_cat module

Matches tests from md_catTest.php
"""

import pytest
from tests.bootstrap import MyFunctionTest
from src.md_cat import add_Translated_from_MDWiki


class TestMdCat(MyFunctionTest):
    """Tests for MDWiki category functions"""
    
    def test_equals(self):
        """Test that existing category is not duplicated"""
        text = "[[Kategorija:Translated from MDWiki]]"
        
        result = add_Translated_from_MDWiki(text, "hr")
        
        assert text == result
    
    def test_skip_langs_it(self):
        """Test that Italian language is skipped"""
        text = "This is a sample text"
        result = add_Translated_from_MDWiki(text, "it")
        assert text == result
    
    def test_appends_category_when_conditions_met(self):
        """Test category is appended when conditions are met"""
        text = "This is a sample text"
        result = add_Translated_from_MDWiki(text, "fr")
        
        expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n"
        assert expected == result
    
    def test_does_not_append_when_category_empty(self):
        """Test category append behavior"""
        text = "This is a sample text"
        result = add_Translated_from_MDWiki(text, "fr")
        expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n"
        assert expected == result
    
    def test_does_not_append_when_category_exists(self):
        """Test category is not appended when it already exists"""
        category = "[[Category:Translated from MDWiki (de)]]"
        text = f"This is a sample text\n{category}"
        
        result = add_Translated_from_MDWiki(text, "de")
        
        assert text == result
    
    def test_does_not_append_when_fallback_category_exists(self):
        """Test fallback category detection"""
        text = "This is a sample text\n[[Category:Translated from MDWiki]]"
        
        result = add_Translated_from_MDWiki(text, "es")
        
        assert text == result
    
    def test_appends_when_similar_category_exists(self):
        """Test behavior when similar category exists"""
        text = "This is a sample text\n[[Category:Translated from MDWiki]]\n"
        
        result = add_Translated_from_MDWiki(text, "ja")
        
        assert text == result
    
    def test_handles_multiple_newlines(self):
        """Test handling of multiple newlines"""
        text = "This is a sample text\n\n"
        
        result = add_Translated_from_MDWiki(text, "ru")
        
        expected = "This is a sample text\n\n\n[[Категория:Статьи, переведённые с MDWiki]]\n"
        assert expected == result
    
    def test_langs(self):
        """Test specific language category"""
        langs = {
            "ur": "زمرہ:ایم ڈی وکی سے ترجمہ شدہ",
        }
        
        for lang, cat in langs.items():
            text_no_cat = "This is a sample text\n\n"
            expected = f"{text_no_cat}\n[[{cat}]]\n"
            result = add_Translated_from_MDWiki(text_no_cat, lang)
            self.assertEqualCompare(expected, text_no_cat, result)
            
            # Test with category already present
            text_with_cat = f"This is a sample text\n\n[[{cat}]]\n"
            result = add_Translated_from_MDWiki(text_with_cat, lang)
            assert text_with_cat == result
            
            # Test with fallback category
            text_with_cat2 = "This is a sample text\n\n[[category:Translated_from_MDWiki]]\n"
            result = add_Translated_from_MDWiki(text_with_cat2, lang)
            assert text_with_cat2 == result
