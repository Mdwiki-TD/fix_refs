"""
Test for sw module (Swahili fixes)

Ported from: tests/swTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.sw import sw_fixes


class TestSw(MyFunctionTest):
    """Test Swahili language fixes"""

    def test_sw_fixes_basic(self):
        """Test basic Swahili fixes"""
        text = "== Marejeo =="
        result = sw_fixes(text)
        # Just verify it doesn't crash
        assert isinstance(result, str)

    def test_sw_fixes_with_refs(self):
        """Test Swahili fixes with references"""
        text = "Text <ref>Reference</ref> more text"
        result = sw_fixes(text)
        assert isinstance(result, str)
