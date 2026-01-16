"""
Test for Bots.refs_utils module

Ported from: tests/Bots/refs_utilsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.bots.refs_utils import (
    str_ends_with, str_starts_with,
    rm_str_from_start_and_end, remove_start_end_quotes
)


class TestRefsUtils(MyFunctionTest):
    """Test reference utilities"""

    def test_str_ends_with(self):
        """Test string ending check"""
        assert str_ends_with("hello world", "world") is True
        assert str_ends_with("hello world", "hello") is False

    def test_str_starts_with(self):
        """Test string starting check"""
        assert str_starts_with("hello world", "hello") is True
        assert str_starts_with("hello world", "world") is False

    def test_rm_str_from_start_and_end(self):
        """Test removing string from start and end"""
        result = rm_str_from_start_and_end('"hello"', '"')
        assert result == "hello"

        result = rm_str_from_start_and_end("'hello'", "'")
        assert result == "hello"

    def test_remove_start_end_quotes(self):
        """Test removing start and end quotes"""
        result = remove_start_end_quotes('"hello"')
        assert result == '"hello"'  # The function normalizes but keeps quotes

        result = remove_start_end_quotes("'hello'")
        assert result == '"hello"'  # Should normalize to double quotes if no double quotes inside
