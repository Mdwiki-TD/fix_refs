from __future__ import annotations

from pathlib import Path

from tests.conftest import assert_equal_compare
from src.bots.remove_duplicate_refs import (
    fix_refs_names,
    remove_Duplicate_refs_With_attrs,
)


FIXTURES_DIR = Path(__file__).parent / "texts"


class TestRemoveDuplicateRefs:
    def test_fix_refs_names(self) -> None:
        tests = [
            {
                "input": "<ref>Simple reference</ref>",
                "expected": "<ref>Simple reference</ref>",
            },
            {
                "input": '<ref name="te1">Reference</ref>',
                "expected": '<ref name="te1">Reference</ref>',
            },
            {
                "input": "<ref name='te2'>Reference</ref>",
                "expected": '<ref name="te2">Reference</ref>',
            },
            {
                "input": '<ref name="te3" group="notes">Reference</ref>',
                "expected": '<ref name="te3" group="notes">Reference</ref>',
            },
            {
                "input": '<ref name>Reference</ref>',
                "expected": '<ref name="">Reference</ref>',
            },
            {
                "input": '<ref name="test\'quote">Reference</ref>',
                "expected": '<ref name="test\'quote">Reference</ref>',
            },
            {
                "input": '<ref name="a">Ref1</ref> <ref name=\'b\'>Ref2</ref>',
                "expected": '<ref name="a">Ref1</ref> <ref name="b">Ref2</ref>',
            },
            {
                "input": '<ref name="te5" group=\'notes\'>Reference</ref>',
                "expected": '<ref name="te5" group="notes">Reference</ref>',
            },
        ]

        for case in tests:
            result = fix_refs_names(case["input"])
            assert_equal_compare(case["expected"], case["input"], result)

    def test_remove_duplicate_refs(self) -> None:
        tests = [
            {
                "input": (
                    'test <ref name="PI2023">{{Cite web|title=DailyMed - '
                    "SKYCLARYS- omaveloxolone capsule}}</ref> adv "
                    '<ref name="PI2023" /> 205<ref name="PI2023">{{Cite web|title='
                    "DailyMed - SKYCLARYS- omaveloxolone capsule}} any test</ref>"
                ),
                "expected": (
                    'test <ref name="PI2023">{{Cite web|title=DailyMed - '
                    "SKYCLARYS- omaveloxolone capsule}}</ref> adv "
                    '<ref name="PI2023" /> 205<ref name="PI2023" />'
                ),
            },
            {
                "input": (
                    'test <ref name="PI2023">{{Cite web|title=DailyMed - '
                    "SKYCLARYS- omaveloxolone capsule}}</ref> adv 205"
                    '<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- '
                    "omaveloxolone capsule}} any test</ref>"
                ),
                "expected": (
                    'test <ref name="PI2023">{{Cite web|title=DailyMed - '
                    "SKYCLARYS- omaveloxolone capsule}}</ref> adv 205"
                    '<ref name="PI2023" />'
                ),
            },
            {
                "input": "<ref>Reference without name1</ref>",
                "expected": "<ref>Reference without name1</ref>",
            },
            {
                "input": '<ref name="test">Refs</ref> <ref name="test">Refs</ref>',
                "expected": '<ref name="test">Refs</ref> <ref name="test" />',
            },
            {
                "input": '<ref name="a">Ref1</ref> <ref name="b">Ref2</ref>',
                "expected": '<ref name="a">Ref1</ref> <ref name="b">Ref2</ref>',
            },
            {
                "input": '<ref name="test" group="notes">Refs2</ref> '
                '<ref name="test" group="notes">Refs2</ref>',
                "expected": '<ref name="test" group="notes">Refs2</ref> '
                '<ref name="test" group="notes" />',
            },
        ]

        for case in tests:
            result = remove_Duplicate_refs_With_attrs(case["input"])
            assert_equal_compare(case["expected"], case["input"], result)

    def test_remove_group_refs_diff(self) -> None:
        input_text = '<ref name="test" group="notes">Ref</ref> <ref name="test" group="notes">Ref</ref>'
        expected = '<ref name="test" group="notes">Ref</ref> <ref name="test" group="notes" />'
        result = remove_Duplicate_refs_With_attrs(input_text)
        assert_equal_compare(expected, input_text, result)

    def test_remove_med_refs(self) -> None:
        input_text = (
            """<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule|url=https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|website=dailymed.nlm.nih.gov|access-date=24 May 2023|archive-date=1 July 2023|archive-url=https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|url-status=live}}</ref> ଅତିକମରେ ୧୬ ବର୍ଷ ବୟସରେ ଏହା ବ୍ୟବହୃତ ହୁଏ ।<ref name="PI2023" /> ଏହା ପାଟିରେ ଦିଆଯାଏ ।<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule|url=https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|website=dailymed.nlm.nih.gov|access-date=24 May 2023|archive-date=1 July 2023|archive-url=https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|url-status=live}}<cite class="citation web cs1" data-ve-ignore="true">[https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e "DailyMed - SKYCLARYS- omaveloxolone capsule"]. ''dailymed.nlm.nih.gov''. [https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e Archived] from the original on 1 July 2023<span class="reference-accessdate">. Retrieved <span class="nowrap">24 May</span> 2023</span>.</cite></ref>"""
        )
        expected = (
            """<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule|url=https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|website=dailymed.nlm.nih.gov|access-date=24 May 2023|archive-date=1 July 2023|archive-url=https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|url-status=live}}</ref> ଅତିକମରେ ୧୬ ବର୍ଷ ବୟସରେ ଏହା ବ୍ୟବହୃତ ହୁଏ ।<ref name="PI2023" /> ଏହା ପାଟିରେ ଦିଆଯାଏ ।<ref name="PI2023" />"""
        )
        result = remove_Duplicate_refs_With_attrs(input_text)
        assert_equal_compare(expected, input_text, result)

    def test_remove_identical_refs_with_group_attribute(self) -> None:
        input_text = '<ref group="notes">Refs3</ref> <ref group="notes">Refs3</ref>'
        expected = '<ref group="notes">Refs3</ref> <ref group="notes" />'
        result = remove_Duplicate_refs_With_attrs(input_text)
        assert_equal_compare(expected, input_text, result)

    def test_file_text(self) -> None:
        text_input = (FIXTURES_DIR / "del_dup_input.txt").read_text(encoding="utf-8")
        text_output = (FIXTURES_DIR / "del_dup_output.txt").read_text(encoding="utf-8")
        result = remove_Duplicate_refs_With_attrs(text_input)
        assert_equal_compare(text_output, text_input, result)
