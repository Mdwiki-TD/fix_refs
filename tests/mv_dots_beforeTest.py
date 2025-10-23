from __future__ import annotations

from typing import Iterable, Tuple

import pytest

from src.helps_bots.mv_dots import move_dots_before_refs
from tests.conftest import assert_equal_compare


def _cases() -> Iterable[Tuple[str, str, str]]:
    yield "This is a sentence<ref>Reference 1</ref>.", "This is a sentence. <ref>Reference 1</ref>", "ar"
    yield "First sentence. Second sentence<ref>Reference 1</ref>.", "First sentence. Second sentence. <ref>Reference 1</ref>", "ar"
    yield "Text<ref>Ref1</ref><ref>Ref2</ref>..", "Text. <ref>Ref1</ref><ref>Ref2</ref>", "ar"
    yield "Text<ref>Reference</ref>,", "Text, <ref>Reference</ref>", "ar"
    yield "Text<ref>Reference</ref>، تجربة", "Text، <ref>Reference</ref> تجربة", "ar"


@pytest.mark.parametrize("input_text, expected, lang", list(_cases()))
def test_move_dots_before_cases(input_text: str, expected: str, lang: str) -> None:
    assert_equal_compare(expected, input_text, move_dots_before_refs(input_text, lang))
