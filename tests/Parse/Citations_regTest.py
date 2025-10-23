from __future__ import annotations

import pytest

from src.Parse.Citations_reg import get_Reg_Citations, get_full_refs, get_name, getShortCitations


class Citations_regTest:
    def test_get_name_with_double_quotes(self) -> None:
        assert get_name('name="test_name"') == "test_name"

    def test_get_name_with_single_quotes(self) -> None:
        assert get_name("name='test_name'") == "test_name"

    def test_get_name_without_quotes(self) -> None:
        assert get_name("name=test_name") == "test_name"

    def test_get_name_with_spaces(self) -> None:
        assert get_name("name = 'test name'") == "test name"

    def test_get_name_empty(self) -> None:
        assert get_name("") == ""
        assert get_name("other_attr=value") == ""

    def test_get_Reg_Citations_with_multiple_refs(self) -> None:
        text = '<ref name="ref1">Content 1</ref> Text <ref name="ref2">Content 2</ref>'
        citations = get_Reg_Citations(text)
        assert len(citations) == 2
        assert citations[0]["name"] == "ref1"
        assert citations[0]["content"] == "Content 1"
        assert citations[0]["tag"] == '<ref name="ref1">Content 1</ref>'

    def test_get_Reg_Citations_with_no_refs(self) -> None:
        assert get_Reg_Citations("No references here") == []

    def test_get_full_refs(self) -> None:
        text = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>'
        full_refs = get_full_refs(text)
        assert full_refs == {
            "ref1": '<ref name="ref1">Content 1</ref>',
            "ref2": '<ref name="ref2">Content 2</ref>',
        }

    def test_getShortCitations(self) -> None:
        text = '<ref name="ref1"/> Text <ref name="ref2"/>'
        short_refs = getShortCitations(text)
        assert len(short_refs) == 2
        assert short_refs[0]["name"] == "ref1"
        assert short_refs[0]["tag"] == '<ref name="ref1"/>'
