from __future__ import annotations

from typing import Callable

import pytest

from src import md_cat
from src.index import fix_page
from tests.conftest import assert_equal_compare


@pytest.fixture(autouse=True)
def stub_mdwiki_category(monkeypatch: pytest.MonkeyPatch) -> Callable[[], None]:
    original = md_cat.get_cats
    if hasattr(original, "cache_clear"):
        original.cache_clear()

    def _get_cats() -> dict:
        return {"hywik": {"title": "Category:Translated from MDWiki"}}

    monkeypatch.setattr(md_cat, "get_cats", _get_cats)
    return _get_cats


class FixpageTest:
    @staticmethod
    def fix_page_wrap(text: str, lang: str) -> str:
        return fix_page(text, "", True, True, False, lang, "", 0)

    def testPart1(self) -> None:
        input_text = (
            "[[Category:Translated from MDWiki]] ռետինոիդներ։ "
            "<ref name=\"NORD2006\" /><ref name=\"Gli2017\" />"
        )
        expected = (
            "[[Category:Translated from MDWiki]] ռետինոիդներ"
            "<ref name=\"NORD2006\" /><ref name=\"Gli2017\" />։"
        )
        assert_equal_compare(expected, input_text, self.fix_page_wrap(input_text, "hy"))
