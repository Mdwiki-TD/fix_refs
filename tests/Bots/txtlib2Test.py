from __future__ import annotations

import json
from pathlib import Path

from src.bots.txtlib2 import extract_templates_and_params


FIXTURES_DIR = Path(__file__).parent / "texts"


class TestTxtlib2:
    def setup_method(self) -> None:
        self.text_input = (FIXTURES_DIR / "txtlib2.txt").read_text(encoding="utf-8")
        self.json_data = json.loads((FIXTURES_DIR / "txtlib2.json").read_text(encoding="utf-8"))
        self.temp_data = extract_templates_and_params(self.text_input)

    def test_input_text_not_empty(self) -> None:
        assert self.text_input, "Input text file is empty!"

    def test_json_data_not_empty(self) -> None:
        assert self.json_data, "JSON file is empty or invalid!"

    def test_temp_data_not_empty(self) -> None:
        assert self.temp_data, "No templates were extracted!"

    def test_first_template_name(self) -> None:
        assert self.temp_data[0]["name"] == "Infobox drug"

    def test_first_template_item_matches_input(self) -> None:
        assert self.temp_data[0]["item"].strip() == self.text_input.strip()

    def test_first_template_params(self) -> None:
        assert self.temp_data[0]["params"] == self.json_data[0]["params"]

    def test_specific_param_values(self) -> None:
        params = self.temp_data[0]["params"]
        assert "tradename" in params
        assert params["tradename"] == "Jaypirca"
        assert "legal_US" in params
        assert params["legal_US"] == "Rx-only"
        assert "CAS_number" in params
        assert params["CAS_number"] == "2101700-15-4"

    def test_count_of_params(self) -> None:
        expected_count = len(self.json_data[0]["params"])
        actual_count = len(self.temp_data[0]["params"])
        assert expected_count == actual_count
