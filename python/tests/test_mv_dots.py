"""
Test mv_dots module

Matches tests from mv_dots_afterTest.php
"""

import pytest
from tests.bootstrap import MyFunctionTest
from src.helps_bots.mv_dots import move_dots_after_refs


class TestMvDots(MyFunctionTest):
    """Tests for moving dots after refs"""
    
    def test_move_dots_after_refs_basic(self):
        """Test basic dot movement"""
        input_text = "text.<ref name='test'/>"
        expected = "text<ref name='test'/>."
        result = move_dots_after_refs(input_text, "en")
        assert expected == result
    
    def test_move_dots_after_refs_multiple(self):
        """Test multiple refs"""
        input_text = "text.<ref name='a'/><ref name='b'/>"
        expected = "text<ref name='a'/><ref name='b'/>."
        result = move_dots_after_refs(input_text, "en")
        assert expected == result
    
    def test_move_dots_after_refs_hy(self):
        """Test Armenian punctuation"""
        input_text = "text։<ref name='test'/>"
        expected = "text<ref name='test'/>։"
        result = move_dots_after_refs(input_text, "hy")
        assert expected == result
    
    def test_move_dots_after_refs_comma(self):
        """Test comma movement"""
        input_text = "text,<ref name='test'/>"
        expected = "text<ref name='test'/>,"
        result = move_dots_after_refs(input_text, "en")
        assert expected == result
