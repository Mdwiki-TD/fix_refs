"""Python port of ``src/WikiParse/src/ParserExternalLinks.php``."""

from __future__ import annotations

import re
from typing import List

from .DataModel.ExternalLink import ExternalLink


class ParserExternalLinks:
    def __init__(self, text: str) -> None:
        self.text = text
        self.links: List[ExternalLink] = []
        self.parse()

    def find_sub_links(self, string: str):
        pattern = re.compile(r"\[(https?://\S+)(.*?)\]", re.DOTALL)
        return pattern.findall(string)

    def parse(self) -> None:
        self.links = []
        for link, tail in self.find_sub_links(self.text):
            self.links.append(ExternalLink(link, tail.strip()))

    def getLinks(self) -> List[ExternalLink]:
        return list(self.links)
