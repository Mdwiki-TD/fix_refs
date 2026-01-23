"""Tests for txtlib2 (txtlib2Test.php)

Converted from tests/Bots/txtlib2Test.php
"""
import json
import pytest
from pathlib import Path
from src.bots.txtlib2 import extract_templates_and_params


class TestTxtLib2:
    """Test cases for template extraction"""

    @pytest.fixture(autouse=True)
    def setup_method(self):
        """Setup test data from files"""
        tests_dir = Path(__file__).parent / "texts"

        with open(tests_dir / "txtlib2.txt", 'r', encoding='utf-8') as f:
            self.text_input = f.read()

        with open(tests_dir / "txtlib2.json", 'r', encoding='utf-8') as f:
            self.json_data = json.load(f)

        self.temp_data = extract_templates_and_params(self.text_input)

    def test_input_text_not_empty(self):
        """Test that input text file is not empty"""
        assert self.text_input, "Input text file is empty!"

    def test_json_data_not_empty(self):
        """Test that JSON file is not empty"""
        assert self.json_data, "JSON file is empty or invalid!"

    def test_temp_data_not_empty(self):
        """Test that templates were extracted"""
        assert self.temp_data, "No templates were extracted!"

    def test_first_template_name(self):
        """Test first template name is 'Infobox drug'"""
        assert self.temp_data[0]["name"] == "Infobox drug", \
            "Template name does not match the expected value."

    def test_first_template_item_matches_input(self):
        """Test extracted template text matches original input"""
        assert self.temp_data[0]["item"].strip() == self.text_input.strip(), \
            "Extracted template text does not match the original input."

    def test_first_template_params(self):
        """Test extracted parameters match expected data from JSON"""
        assert self.json_data[0]["params"] == self.temp_data[0]["params"], \
            "Extracted parameters do not match the expected data."

    def test_specific_param_values(self):
        """Test specific parameter values"""
        params = self.temp_data[0]["params"]
        assert "tradename" in params
        assert params["tradename"] == "Jaypirca"

        assert "legal_US" in params
        assert params["legal_US"] == "Rx-only"

        assert "CAS_number" in params
        assert params["CAS_number"] == "2101700-15-4"

    def test_count_of_params(self):
        """Test number of extracted parameters matches expected count"""
        expected_count = len(self.json_data[0]["params"])
        actual_count = len(self.temp_data[0]["params"])
        assert expected_count == actual_count, \
            "Number of extracted parameters does not match the expected count."
