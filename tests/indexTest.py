from __future__ import annotations

from pathlib import Path

import pytest

import fix_refs.work as work
from src import md_cat
from tests.conftest import assert_equal_compare


FIXTURES_DIR = Path(__file__).parent / "texts" / "indexTest"


@pytest.fixture(autouse=True)
def stub_settings(monkeypatch: pytest.MonkeyPatch) -> None:
    monkeypatch.setattr(
        work,
        "load_settings_new",
        lambda: {"hy": {"move_dots": "1", "expend": "1", "add_en_lang": "0"}},
    )
    original = md_cat.get_cats
    if hasattr(original, "cache_clear"):
        original.cache_clear()
    monkeypatch.setattr(
        md_cat, "get_cats", lambda: {"hywik": {"title": "Category:Translated from MDWiki"}}
    )


class indexTest:
    @staticmethod
    def fix_page_wrap(text: str, lang: str) -> str:
        return work.DoChangesToText1("", "", text, lang, 0)

    def _run_case(self, case: str, lang: str) -> None:
        case_dir = FIXTURES_DIR / case
        input_text = (case_dir / "input.txt").read_text(encoding="utf-8")
        expected = (case_dir / "expected.txt").read_text(encoding="utf-8")
        result = self.fix_page_wrap(input_text, lang)
        (case_dir / "output.txt").write_text(result, encoding="utf-8")
        assert_equal_compare(expected, input_text, result)

    def testPart1(self) -> None:
        self._run_case("1", "hy")

    def testPart2(self) -> None:
        self._run_case("2", "hy")

    def testPart3(self) -> None:
        input_text = (
            "[[Category:Translated from MDWiki]] ռետինոիդներ։ "
            "<ref name=\"NORD2006\" /><ref name=\"Gli2017\" />"
        )
        expected = (
            "[[Category:Translated from MDWiki]] ռետինոիդներ"
            "<ref name=\"NORD2006\" /><ref name=\"Gli2017\" />։"
        )
        assert_equal_compare(expected, input_text, self.fix_page_wrap(input_text, "hy"))

    def testPart4(self) -> None:
        input_text = (
            "[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին "
            "Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ <ref name=\"Sc2011\">{{Cite book|last="
            "Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|"
            "publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|"
            "language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id="
            "tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։"
        )
        expected = (
            "[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա "
            "Օլիվեր Հարթի օրագրային գրառման մեջ<ref name=\"Sc2011\">{{Cite book|last=Schachner|first=Lawrence A.|last2="
            "Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|"
            "isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|"
            "archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|"
            "archive-date=November 5, 2017}}</ref>։"
        )
        assert_equal_compare(expected, input_text, self.fix_page_wrap(input_text, "hy"))
