"""Python port of ``src/Parse/Citations.php``."""

from __future__ import annotations

import re
from typing import List


class CitationOld:
    """Replicates the legacy citation container from the PHP codebase."""

    def __init__(self, text: str, options: str = "", cite_text: str = "") -> None:
        self.text = text
        self.options = options
        self.cite_text = cite_text

    def getOriginalText(self) -> str:
        return self.cite_text

    def getContent(self) -> str:
        return self.text

    def getAttributes(self) -> str:
        return self.options

    def toString(self) -> str:
        return f"<ref {self.options.strip()}>{self.text}</ref>"


class ParserCitationsOld:
    """Parse ``<ref>`` blocks from a snippet of wikitext."""

    def __init__(self, text: str) -> None:
        self.text = text
        self.citations: List[CitationOld] = []
        self.parse()

    def find_sub_citations(self, string: str) -> List[re.Match[str]]:
        pattern = re.compile(r"<ref([^/>]*?)>(.+?)</ref>", re.IGNORECASE | re.DOTALL)
        return list(pattern.finditer(string))

    def parse(self) -> None:
        matches = self.find_sub_citations(self.text)
        citations: List[CitationOld] = []
        for match in matches:
            citation = CitationOld(match.group(2), match.group(1), match.group(0))
            citations.append(citation)
        self.citations = citations

    def getCitations(self) -> List[CitationOld]:
        return list(self.citations)


def getCitationsOld(text: str) -> List[CitationOld]:
    parser = ParserCitationsOld(text)
    return parser.getCitations()
