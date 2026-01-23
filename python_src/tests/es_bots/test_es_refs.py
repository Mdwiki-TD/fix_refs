"""Tests for Spanish references (es_refsTest.php)

Converted from tests/es_bots/es_refsTest.php
"""
import pytest
from pathlib import Path
from src.lang_bots.es_helpers import mv_es_refs


class TestEsRefs:
    """Test cases for Spanish reference moving"""

    def test_file_text(self):
        """Test with file input for Spanish references"""
        tests_dir = Path(__file__).parent / "texts"

        with open(tests_dir / "es_refs_input.txt", 'r', encoding='utf-8') as f:
            text_input = f.read()

        with open(tests_dir / "es_refs_output.txt", 'r', encoding='utf-8') as f:
            text_output = f.read()

        # Optionally write output for comparison
        output_file = tests_dir / "es_refs_output_fixed.txt"
        result = mv_es_refs(text_input)
        # Uncomment to write output file:
        # with open(output_file, 'w', encoding='utf-8') as f:
        #     f.write(result)

        # Normalize line endings for comparison
        assert result.replace('\r\n', '\n') == text_output.replace('\r\n', '\n')
