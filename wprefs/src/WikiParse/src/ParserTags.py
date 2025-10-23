"""Python port of ``src/WikiParse/src/ParserTags.php``."""

from __future__ import annotations

import re
from typing import Dict, List

from .DataModel.Tag import Tag


class ParserTags:
    def __init__(self, text: str, tagname: str = "") -> None:
        self.tagname = tagname
        self.text = text
        self.tags: List[Tag] = []
        self.parse()

    def find_sub_tags(self, string: str) -> List[Dict[str, str]]:
        matches: List[Dict[str, str]] = []

        standard_pattern = re.compile(r"<(?P<tag>[a-zA-Z0-9]+)(?P<attrs>\s[^>]*)?>(?P<content>.*?)</(?P=tag)>", re.DOTALL)
        for match in standard_pattern.finditer(string):
            matches.append(
                {
                    "original": match.group(0),
                    "name": match.group("tag"),
                    "attributes": match.group("attrs") or "",
                    "content": match.group("content"),
                    "selfClosing": False,
                }
            )

        self_closing_pattern = re.compile(r"<(?P<tag>[a-zA-Z0-9]+)(?P<attrs>\s[^>]*)?\s*/>", re.DOTALL)
        for match in self_closing_pattern.finditer(string):
            matches.append(
                {
                    "original": match.group(0),
                    "name": match.group("tag"),
                    "attributes": match.group("attrs") or "",
                    "content": "",
                    "selfClosing": True,
                }
            )

        return matches

    def parse(self) -> None:
        self.tags = []
        for citation_data in self.find_sub_tags(self.text):
            if self.tagname and citation_data["name"].strip() != self.tagname.strip():
                continue
            tag = Tag(
                citation_data["name"],
                citation_data["content"],
                citation_data["attributes"],
                citation_data["original"],
                citation_data["selfClosing"],
            )
            self.tags.append(tag)

    def getTags(self, name: str | None = None) -> List[Tag]:
        if not name:
            return list(self.tags)
        return [tag for tag in self.tags if tag.getName() == name]
