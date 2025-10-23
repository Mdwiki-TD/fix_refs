from __future__ import annotations

from pathlib import Path

import pytest

from src.bots.expend_refs import refs_expend_work


FIXTURES_DIR = Path(__file__).parent / "texts"


class TestExpendRefs:
    def setup_method(self) -> None:
        self.text_input = (FIXTURES_DIR / "expend_input.txt").read_text(encoding="utf-8")
        self.text_output = (FIXTURES_DIR / "expend_output.txt").read_text(encoding="utf-8")
        self.refs_expends = refs_expend_work(self.text_input)

    def test_input_text_not_empty(self) -> None:
        assert self.text_input, "Input text file is empty!"

    def test_text_output_not_empty(self) -> None:
        assert self.text_output, "output file is empty!"

    def test_not_same(self) -> None:
        assert self.text_input != self.text_output, "Input and output are the same!"

    def test_expend_refs_not_empty(self) -> None:
        assert self.refs_expends, "output file is empty!"

    def test_expend_refs_the_same_as_output(self) -> None:
        assert self.refs_expends == self.text_output, "Expend refs not working!"

    @pytest.mark.parametrize(
        "input_text, expected",
        [
            (
                '<ref name="ref1">Full content</ref> Text <ref name="ref1"/>',
                '<ref name="ref1">Full content</ref> Text <ref name="ref1">Full content</ref>',
            ),
            (
                '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>',
                '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>',
            ),
            (
                '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> '
                'Text <ref name="ref1"/> <ref name="ref2"/>',
                '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> '
                'Text <ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>',
            ),
            (
                '<ref name="ref1">  Full content  </ref> Text <ref name="ref1"/>',
                '<ref name="ref1">  Full content  </ref> Text <ref name="ref1">  Full content  </ref>',
            ),
            (
                '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> '
                'Text <ref name="ref1"/>',
                '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> '
                'Text <ref name="ref1">Content with "quotes" & \'apostrophes\'</ref>',
            ),
        ],
    )
    def test_refs_expend_work_cases(self, input_text: str, expected: str) -> None:
        assert refs_expend_work(input_text) == expected

    def test_refs_expend_work_with_alltext_parameter(self) -> None:
        first = 'Text <ref name="ref1"/>'
        alltext = '<ref name="ref1">Full content</ref>'
        expected = 'Text <ref name="ref1">Full content</ref>'
        assert refs_expend_work(first, alltext) == expected

    def test_refs_expend_work_with_empty_input(self) -> None:
        assert refs_expend_work("") == ""

    def test_refs_expend_work_with_no_refs(self) -> None:
        input_text = "No references here"
        assert refs_expend_work(input_text) == input_text
