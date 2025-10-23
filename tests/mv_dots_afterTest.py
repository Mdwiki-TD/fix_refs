from __future__ import annotations

from typing import Iterable, Tuple

import pytest

from src.helps_bots.mv_dots import move_dots_after_refs
from tests.conftest import assert_equal_compare


def _cases() -> Iterable[Tuple[str, str, str]]:
    yield "This is a sentence。<ref>Reference 1</ref>", "This is a sentence<ref>Reference 1</ref>。", "en"
    yield "First sentence. Second sentence.<ref>Reference 1</ref>", "First sentence. Second sentence<ref>Reference 1</ref>.", "en"
    yield "Text।<ref>Ref1</ref><ref>Ref2</ref>", "Text<ref>Ref1</ref><ref>Ref2</ref>।", "en"
    yield "Text<ref>Reference</ref>", "Text<ref>Reference</ref>", "en"
    yield "Text, <ref>Reference</ref>", "Text<ref>Reference</ref>,", "en"
    yield "Text.  <ref>Reference</ref>", "Text<ref>Reference</ref>.", "en"
    yield "Text.<ref name=\"ref1\" />", "Text<ref name=\"ref1\" />.", "en"
    yield "Text. <ref>Ref1</ref> <ref>Ref2</ref>", "Text<ref>Ref1</ref> <ref>Ref2</ref>.", "en"
    yield "This is a sentence. This is another sentence<ref>Reference</ref>", "This is a sentence. This is another sentence<ref>Reference</ref>", "en"
    yield "Text.,<ref>Reference</ref>", "Text<ref>Reference</ref>.,", "en"
    yield "", "", "en"
    yield "This is a sentence.", "This is a sentence.", "en"
    yield "Text.<ref name=\"ref1\" group=\"group1\">Reference content</ref>", "Text<ref name=\"ref1\" group=\"group1\">Reference content</ref>.", "en"
    yield "Text.<ref>Reference with <i>italic</i> text</ref>", "Text<ref>Reference with <i>italic</i> text</ref>.", "en"
    yield "这是句子。<ref>参考文献1</ref>", "这是句子<ref>参考文献1</ref>。", "en"
    yield "यह वाक्य है।<ref>संदर्भ 1</ref>", "यह वाक्य है<ref>संदर्भ 1</ref>।", "en"
    yield "Տեքստ.,<ref>Հղում</ref>", "Տեքստ<ref>Հղում</ref>.,", "hy"
    yield "Text.<ref>Reference</ref>", "Text<ref>Reference</ref>.", "en"
    yield "First sentence.<ref>Ref1</ref> Second sentence.<ref>Ref2</ref>", "First sentence<ref>Ref1</ref>. Second sentence<ref>Ref2</ref>.", "en"
    yield ".<ref>Reference</ref>", "<ref>Reference</ref>.", "en"
    yield "Text. <ref>Reference</ref>", "Text<ref>Reference</ref>.", "en"
    yield "Text, <ref>Reference</ref>", "Text<ref>Reference</ref>,", "en"
    yield "Text.<ref>Reference</ref>", "Text<ref>Reference</ref>.", "en"
    yield "Text,<ref>Reference</ref>", "Text<ref>Reference</ref>,", "en"
    yield "Text.<ref>Ref1</ref><ref>Ref2</ref>", "Text<ref>Ref1</ref><ref>Ref2</ref>.", "en"
    yield "Text. <ref>Ref1</ref> <ref>Ref2</ref>", "Text<ref>Ref1</ref> <ref>Ref2</ref>.", "en"


@pytest.mark.parametrize("input_text, expected, lang", list(_cases()))
def test_move_dots_after_cases(input_text: str, expected: str, lang: str) -> None:
    assert_equal_compare(expected, input_text, move_dots_after_refs(input_text, lang))
