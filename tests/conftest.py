"""Shared pytest helpers for the test-suite.

This module provides a drop-in replacement for
``MyFunctionTest::assertEqualCompare`` from the legacy PHPUnit suite.
The helper normalises newline characters and mimics the additional
assertion that ensures a transformation actually changed the provided
input when a different result was expected.
"""

from __future__ import annotations

from pathlib import Path
from typing import TypeVar

import importlib
import sys

import pytest


_ROOT = Path(__file__).resolve().parent.parent
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))

# Ensure legacy module paths remain available after the PHP to Python port.
_test_bot = importlib.import_module("src.test_bot")
sys.modules.setdefault("src.TestBot", _test_bot)


T = TypeVar("T")


def _normalize_newlines(value: T) -> T:
    if isinstance(value, str):
        return value.replace("\r\n", "\n")  # type: ignore[return-value]
    return value


def assert_equal_compare(expected: T, original: T, result: T) -> None:
    """Assert that ``result`` matches ``expected`` after normalising newlines.

    The helper mirrors the behaviour of the PHP implementation: the
    assertion fails early if the transformation yields the original
    input while a different value was expected.  This guards against
    silently passing tests when no modification was performed.
    """

    expected_norm = _normalize_newlines(expected)
    result_norm = _normalize_newlines(result)
    original_norm = _normalize_newlines(original)

    if result_norm == original_norm and result_norm != expected_norm:
        pytest.fail(
            "No changes were made! The function returned the input unchanged:\n"
            f"{result_norm!r}"
        )

    assert result_norm == expected_norm, (
        "Unexpected result:\n"
        f"expected={expected_norm!r}, result={result_norm!r}"
    )


__all__ = ["assert_equal_compare"]
