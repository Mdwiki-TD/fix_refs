from __future__ import annotations

import json
from pathlib import Path

from src.infoboxes.infobox2 import expend_new, make_tempse


FIXTURES_DIR = Path(__file__).parent / "texts_infobox2"


class infobox2Test:
    def test_expend_new_FileText(self) -> None:
        text_input = (FIXTURES_DIR / "infobox2_input.txt").read_text(encoding="utf-8")
        text_output = (FIXTURES_DIR / "infobox2_output.txt").read_text(encoding="utf-8")
        result = expend_new(text_input)
        (FIXTURES_DIR / "infobox2_fixed.txt").write_text(result, encoding="utf-8")
        assert text_output.replace("\r\n", "\n").strip() == result.replace("\r\n", "\n").strip()

    def test_make_tempse_FileText(self) -> None:
        text_input = (FIXTURES_DIR / "infobox2_tempse_input.txt").read_text(encoding="utf-8")
        text_output = json.loads((FIXTURES_DIR / "infobox2_tempse_output.json").read_text(encoding="utf-8"))
        result = make_tempse(text_input)
        (FIXTURES_DIR / "infobox2_tempse_fixed.json").write_text(
            json.dumps(result, ensure_ascii=False, indent=4),
            encoding="utf-8",
        )
        assert result == text_output
