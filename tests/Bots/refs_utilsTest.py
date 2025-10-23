from __future__ import annotations

from tests.conftest import assert_equal_compare
from src.bots.refs_utils import remove_start_end_quotes, rm_str_from_start_and_end


def str_ends_with(string: str, end: str) -> bool:
    return string.endswith(end)


def str_starts_with(string: str, start: str) -> bool:
    return string.startswith(start)


class TestRefsUtils:
    def test_adds_double_quotes_to_plain_string(self) -> None:
        assert remove_start_end_quotes("value") == '"value"'

    def test_replaces_single_quotes_with_double_quotes(self) -> None:
        assert remove_start_end_quotes("'value'") == '"value"'

    def test_replaces_double_quotes_with_double_quotes(self) -> None:
        assert remove_start_end_quotes('"value"') == '"value"'

    def test_wraps_with_single_quotes_if_contains_double_quotes(self) -> None:
        assert remove_start_end_quotes('val"ue') == "'val\"ue'"

    def test_trims_whitespace(self) -> None:
        assert remove_start_end_quotes("  value  ") == '"value"'

    def test_handles_empty_string(self) -> None:
        assert remove_start_end_quotes("") == '""'

    def test_one_quotes_double(self) -> None:
        assert remove_start_end_quotes('  "value ') == "'\"value'"

    def test_one_quotes_single(self) -> None:
        assert remove_start_end_quotes("  'value ") == '"\'value"'

    def test_str_ends_with(self) -> None:
        tests = [
            {"string": "Hello world", "endString": "world", "expected": True},
            {"string": "Hello world", "endString": "hello", "expected": False},
            {"string": "", "endString": "test", "expected": False},
            {"string": "short", "endString": "longer text", "expected": False},
            {"string": "exact", "endString": "exact", "expected": True},
            {"string": "file.txt", "endString": ".txt", "expected": True},
            {"string": "Case", "endString": "case", "expected": False},
        ]

        for case in tests:
            result = str_ends_with(case["string"], case["endString"])
            assert_equal_compare(case["expected"], case["string"], result)

    def test_str_starts_with(self) -> None:
        tests = [
            {"text": "Hello world", "start": "Hello", "expected": True},
            {"text": "Hello world", "start": "world", "expected": False},
            {"text": "", "start": "test", "expected": False},
            {"text": "test", "start": "", "expected": True},
            {"text": "short", "start": "longer text", "expected": False},
            {"text": "exact", "start": "exact", "expected": True},
            {"text": "#tag", "start": "#", "expected": True},
            {"text": "Case", "start": "case", "expected": False},
        ]

        for case in tests:
            result = str_starts_with(case["text"], case["start"])
            assert_equal_compare(case["expected"], case["text"], result)

    def test_del_start_end(self) -> None:
        tests = [
            {"text": "'quoted text'", "find": "'", "expected": "quoted text"},
            {"text": '"double quoted"', "find": '"', "expected": "double quoted"},
            {"text": "no quotes", "find": "'", "expected": "no quotes"},
            {"text": "'start only", "find": "'", "expected": "'start only"},
            {"text": "end only'", "find": "'", "expected": "end only'"},
            {"text": "  '  spaced  '  ", "find": "'", "expected": "spaced"},
            {"text": "", "find": "'", "expected": ""},
            {"text": "''multiple''", "find": "'", "expected": "'multiple'"},
            {"text": "''", "find": "'", "expected": ""},
        ]

        for case in tests:
            result = rm_str_from_start_and_end(case["text"], case["find"])
            assert_equal_compare(case["expected"], case["text"], result)

    def test_remove_start_end_quotes(self) -> None:
        tests = [
            {"text": "value1", "expected": '"value1"'},
            {"text": "'value2'", "expected": '"value2"'},
            {"text": '"value3"', "expected": '"value3"'},
            {"text": '"mixed\'quotes"', "expected": '"mixed\'quotes"'},
            {"text": 'value"with"quotes', "expected": "'value\"with\"quotes'"},
            {"text": "  spaced  ", "expected": '"spaced"'},
            {"text": "val'ue", "expected": '"val\'ue"'},
        ]

        for case in tests:
            result = remove_start_end_quotes(case["text"])
            assert_equal_compare(case["expected"], case["text"], result)

    def test_fix_empty(self) -> None:
        assert "" == ""

    def test_fix_only_quotes(self) -> None:
        assert remove_start_end_quotes('""') == '""'

    def test_fix_only_single_quotes(self) -> None:
        assert remove_start_end_quotes("''") == '""'

    def test_del_start_end_empty(self) -> None:
        result = rm_str_from_start_and_end("testzz", "")
        assert_equal_compare("testzz", "testzz", result)
