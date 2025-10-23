from __future__ import annotations

from pathlib import Path

from src.es_bots.es_refs import mv_es_refs


FIXTURES_DIR = Path(__file__).parent / "texts"


class es_refsTest:
    def testFileText(self) -> None:
        text_input = (FIXTURES_DIR / "es_refs_input.txt").read_text(encoding="utf-8")
        text_output = (FIXTURES_DIR / "es_refs_output.txt").read_text(encoding="utf-8")
        result = mv_es_refs(text_input)
        (FIXTURES_DIR / "es_refs_output_fixed.txt").write_text(result, encoding="utf-8")
        assert text_output.replace("\r\n", "\n") == result.replace("\r\n", "\n")
