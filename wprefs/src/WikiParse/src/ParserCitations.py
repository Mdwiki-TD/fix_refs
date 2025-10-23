"""Python port of ``src/WikiParse/src/ParserCitations.php``."""

from __future__ import annotations

from typing import List

from .DataModel.Tag import Tag
from .ParserTags import ParserTags


class ParserCitations:
    def __init__(self, text: str) -> None:
        self.text = text
        self.citations: List[Tag] = []
        self.parse()

    def parse(self) -> None:
        tag_parser = ParserTags(self.text, "ref")
        self.citations = tag_parser.getTags()

    def getCitations(self) -> List[Tag]:
        return list(self.citations)
