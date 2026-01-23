"""Tests for infobox2 (infobox2Test.php)

Converted from tests/infoboxes/infobox2Test.php

Note: This test requires the infobox2 Python module which may need to be implemented.
"""
import json
from pathlib import Path
from src.infobox import expend_infobox as ei_module

expend_new = ei_module.expend_new
make_tempse = ei_module.make_tempse

tests_dir = Path(__file__).parent / "texts_infobox2"


class TestInfobox2:
    """Test cases for infobox2 template processing"""

    def test_expend_new_file_text(self):
        """Test expend_new with file input"""

        with open(tests_dir / "infobox2_input.txt", 'r', encoding='utf-8') as f:
            text_input = f.read()

        with open(tests_dir / "infobox2_output.txt", 'r', encoding='utf-8') as f:
            text_output = f.read()

        result = expend_new(text_input)

        result = result.replace('\r\n', '\n')
        text_output = text_output.replace('\r\n', '\n')
        assert text_output.strip() == result.strip()

    def test_make_tempse_file_text(self):
        """Test make_tempse with file input"""

        with open(tests_dir / "infobox2_tempse_input.txt", 'r', encoding='utf-8') as f:
            text_input = f.read()

        with open(tests_dir / "infobox2_tempse_output.json", 'r', encoding='utf-8') as f:
            text_output = json.load(f)

        result = make_tempse(text_input)

        assert text_output == result
