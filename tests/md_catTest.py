from __future__ import annotations

from typing import Callable, Dict

import pytest

from src import md_cat
from tests.conftest import assert_equal_compare


@pytest.fixture
def mdwiki_categories(monkeypatch: pytest.MonkeyPatch) -> Callable[[Dict[str, Dict[str, str]]], None]:
    def apply(mapping: Dict[str, Dict[str, str]]) -> None:
        original = md_cat.get_cats
        if hasattr(original, "cache_clear"):
            original.cache_clear()

        def _get_cats() -> Dict[str, Dict[str, str]]:
            return mapping

        monkeypatch.setattr(md_cat, "get_cats", _get_cats)

    return apply


class md_catTest:
    def testEquals(self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]) -> None:
        mdwiki_categories({"hrwiki": {"title": "Category:Translated from MDWiki"}})
        text = "[[Kategorija:Translated from MDWiki]]"
        assert md_cat.add_Translated_from_MDWiki(text, "hr") == text

    def testSkipLangsIt(self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]) -> None:
        mdwiki_categories({})
        text = "This is a sample text"
        assert md_cat.add_Translated_from_MDWiki(text, "it") == text

    def testAppendsCategoryWhenConditionsMet(
        self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]
    ) -> None:
        mdwiki_categories({"frwiki": {"title": "Catégorie:Traduit de MDWiki"}})
        text = "This is a sample text"
        expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n"
        assert md_cat.add_Translated_from_MDWiki(text, "fr") == expected

    def testDoesNotAppendWhenCategoryEmpty(
        self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]
    ) -> None:
        mdwiki_categories({"frwiki": {"title": "Catégorie:Traduit de MDWiki"}})
        text = "This is a sample text"
        expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n"
        assert md_cat.add_Translated_from_MDWiki(text, "fr") == expected

    def testDoesNotAppendWhenCategoryExists(
        self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]
    ) -> None:
        mdwiki_categories({"dewiki": {"title": "Category:Translated from MDWiki (de)"}})
        category = "[[Category:Translated from MDWiki (de)]]"
        text = f"This is a sample text\n{category}"
        assert md_cat.add_Translated_from_MDWiki(text, "de") == text

    def testDoesNotAppendWhenFallbackCategoryExists(
        self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]
    ) -> None:
        mdwiki_categories({"eswiki": {"title": "Category:Translated from MDWiki"}})
        text = "This is a sample text\n[[Category:Translated from MDWiki]]"
        assert md_cat.add_Translated_from_MDWiki(text, "es") == text

    def testAppendsWhenSimilarCategoryExists(
        self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]
    ) -> None:
        mdwiki_categories({"jawiki": {"title": "Category:Translated from MDWiki"}})
        text = "This is a sample text\n[[Category:Translated from MDWiki]]\n"
        assert md_cat.add_Translated_from_MDWiki(text, "ja") == text

    def testHandlesMultipleNewlines(
        self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]
    ) -> None:
        mdwiki_categories({"ruwiki": {"title": "Категория:Статьи, переведённые с MDWiki"}})
        text = "This is a sample text\n\n"
        expected = "This is a sample text\n\n\n[[Категория:Статьи, переведённые с MDWiki]]\n"
        assert md_cat.add_Translated_from_MDWiki(text, "ru") == expected

    def testLangs(self, mdwiki_categories: Callable[[Dict[str, Dict[str, str]]], None]) -> None:
        langs = {"urwiki": {"title": "زمرہ:ایم ڈی وکی سے ترجمہ شدہ"}}
        mdwiki_categories(langs)
        text_no_cat = "This is a sample text\n\n"
        expected = "This is a sample text\n\n\n[[زمرہ:ایم ڈی وکی سے ترجمہ شدہ]]\n"
        result = md_cat.add_Translated_from_MDWiki(text_no_cat, "ur")
        assert_equal_compare(expected, text_no_cat, result)

        text_with_cat = "This is a sample text\n\n[[زمرہ:ایم ڈی وکی سے ترجمہ شدہ]]\n"
        assert md_cat.add_Translated_from_MDWiki(text_with_cat, "ur") == text_with_cat

        text_with_fallback = "This is a sample text\n\n[[category:Translated_from_MDWiki]]\n"
        assert md_cat.add_Translated_from_MDWiki(text_with_fallback, "ur") == text_with_fallback
