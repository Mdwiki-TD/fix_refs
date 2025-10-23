from __future__ import annotations

from tests.conftest import assert_equal_compare
from src.bots.mini_fixes_bot import (
    fix_preffix,
    fix_sections_titles,
    refs_tags_spaces,
    remove_space_before_ref_tags,
)


class TestMiniFixesBot:
    def test_sections_titles_ru(self) -> None:
        texts = [
            {
                "old": "== Ссылки  ==\n====Ссылки====\n\n== Примечания 3 ==",
                "new": "== Примечания ==\n==== Примечания ====\n\n== Примечания 3 ==",
            },
            {
                "old": "== Ссылки  ==\n====Ссылки====\n\n== Примечания 3 ==",
                "new": "== Примечания ==\n==== Примечания ====\n\n== Примечания 3 ==",
            },
        ]

        for case in texts:
            new_text = fix_sections_titles(case["old"], "ru")
            assert_equal_compare(case["new"], case["old"], new_text)

    def test_sections_titles_hr(self) -> None:
        texts = [
            {
                "old": "== Reference  ==",
                "new": "== Izvori ==",
            },
            {
                "old": "== Reference  ==\n\n====References====\n\n== References 3 ==",
                "new": "== Izvori ==\n\n==== Izvori ====\n\n== References 3 ==",
            },
        ]

        for case in texts:
            new_text = fix_sections_titles(case["old"], "hr")
            assert_equal_compare(case["new"], case["old"], new_text)

    def test_sections_titles_sw(self) -> None:
        text = "== Marejeleo 1 ==\n\n====Marejeleo====\n\n=== Marejeleo ==="
        new = "== Marejeleo 1 ==\n\n==== Marejeo ====\n\n=== Marejeo ==="
        new_text = fix_sections_titles(text, "sw")
        assert_equal_compare(new, text, new_text)

    def test_remove_space_before_ref_tags(self) -> None:
        tests = [
            {
                "text": "جملة. <ref>مرجع</ref>",
                "lang": "ar",
                "expected": "جملة.<ref>مرجع</ref>",
            },
            {
                "text": "جملة, <ref>مرجع</ref>",
                "lang": "ar",
                "expected": "جملة,<ref>مرجع</ref>",
            },
            {
                "text": "جملة.   <ref>مرجع</ref>",
                "lang": "sw",
                "expected": "جملة.<ref>مرجع</ref>",
            },
            {
                "text": "جملة.<ref>مرجع</ref>",
                "lang": "bn",
                "expected": "جملة.<ref>مرجع</ref>",
            },
            {
                "text": "جملة। <ref>مرجع</ref>",
                "lang": "ar",
                "expected": "جملة।<ref>مرجع</ref>",
            },
            {
                "text": "نص عادي بدون مراجع",
                "lang": "ar",
                "expected": "نص عادي بدون مراجع",
            },
        ]

        for case in tests:
            result = remove_space_before_ref_tags(case["text"], case["lang"])
            assert_equal_compare(case["expected"], case["text"], result)

    def test_refs_tags_spaces(self) -> None:
        tests = [
            {
                "text": "نص</ref> <ref>مرجع</ref>",
                "expected": "نص</ref><ref>مرجع</ref>",
            },
            {
                "text": "نص</ref>   <ref>مرجع</ref>",
                "expected": "نص</ref><ref>مرجع</ref>",
            },
            {
                "text": 'نص<ref name="test"/> <ref>مرجع</ref>',
                "expected": 'نص<ref name="test"/><ref>مرجع</ref>',
            },
            {
                "text": "نص> <ref>مرجع</ref>",
                "expected": "نص><ref>مرجع</ref>",
            },
            {
                "text": "نص عادي <ref>مرجع</ref> ونص آخر",
                "expected": "نص عادي <ref>مرجع</ref> ونص آخر",
            },
            {
                "text": "</ref> <ref name=A/> <ref name=B>",
                "expected": "</ref><ref name=A/><ref name=B>",
            },
        ]

        for case in tests:
            result = refs_tags_spaces(case["text"])
            assert_equal_compare(case["expected"], case["text"], result)

    def test_fix_preffix(self) -> None:
        tests = [
            {
                "text": "[[:en:Example|مثال]]",
                "lang": "ar",
                "expected": "[[Example|مثال]]",
            },
            {
                "text": "[[:ar:مثال|نص]]",
                "lang": "ar",
                "expected": "[[مثال|نص]]",
            },
            {
                "text": "[[:fr:Exemple|نص]]",
                "lang": "ar",
                "expected": "[[:fr:Exemple|نص]]",
            },
            {
                "text": "[[:en:Page1|نص1]] و [[:ar:صفحة|نص2]]",
                "lang": "ar",
                "expected": "[[Page1|نص1]] و [[صفحة|نص2]]",
            },
            {
                "text": "[[:en:Example]]",
                "lang": "ar",
                "expected": "[[Example]]",
            },
            {
                "text": "نص عادي بدون روابط",
                "lang": "ar",
                "expected": "نص عادي بدون روابط",
            },
        ]

        for case in tests:
            result = fix_preffix(case["text"], case["lang"])
            assert_equal_compare(case["expected"], case["text"], result)
