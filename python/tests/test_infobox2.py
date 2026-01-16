"""
Test for infoboxes.infobox2 module

Ported from: tests/infoboxes/infobox2Test.php
"""

from tests.bootstrap import MyFunctionTest
from src.infoboxes.infobox2 import make_tempse, expend_new


class TestInfobox2(MyFunctionTest):
    """Test advanced infobox utilities"""

    def test_make_tempse_basic(self):
        """Test making template structure"""
        text = "{{Template|param=value}}"
        result = make_tempse(text)
        assert isinstance(result, dict)
        assert "tempse_by_u" in result
        assert "tempse" in result

    def test_expend_new_basic(self):
        """Test expanding new infobox"""
        text = "Article text"
        result = expend_new(text)
        assert isinstance(result, str)
