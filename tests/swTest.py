from __future__ import annotations

from src.sw import sw_fixes


class swTest:
    def test_fix_temps_and_months_1(self) -> None:
        assert sw_fixes("== Marejeleo ==") == "== Marejeo =="

    def test_fix_temps_and_months_2(self) -> None:
        assert sw_fixes("==Marejeleo==") == "== Marejeo =="

    def test_Extra_spaces_around_the_word(self) -> None:
        assert sw_fixes("====   Marejeleo   ====") == "==== Marejeo ===="

    def test_Case_insensitivity_mixed(self) -> None:
        assert sw_fixes("====== MaReJeLeO ======") == "====== Marejeo ======"

    def test_additional_text(self) -> None:
        assert sw_fixes("== Marejeleo na Maoni ==") == "== Marejeo na Maoni =="

    def testSwFixes(self) -> None:
        tests = [
            ("=== marejeleo ===", "=== Marejeo ==="),
            ("== Marejeleo ==\nSome text\n== marejeleo ==", "== Marejeo ==\nSome text\n== Marejeo =="),
            ("This is a section: == Marejeleo ==", "This is a section: == Marejeo =="),
            ("== MarejeleoMengine ==", "== MarejeleoMengine =="),
            ("=== Viungo ===", "=== Viungo ==="),
            ("", ""),
            ("This is just regular text", "This is just regular text"),
            ("==     Marejeleo     ==", "== Marejeo =="),
            ("==\tMarejeleo\t==", "== Marejeo =="),
            ("== \t \n Marejeleo \n \t ==", "== Marejeo =="),
            ("== Marejeleo == and == marejeleo ==", "== Marejeo == and == Marejeo =="),
        ]
        for input_text, expected in tests:
            assert sw_fixes(input_text) == expected
