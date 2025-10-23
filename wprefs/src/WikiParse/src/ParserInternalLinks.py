"""Python port of ``src/WikiParse/src/ParserInternalLinks.php``."""

from __future__ import annotations

from typing import List

from .DataModel.InternalLink import InternalLink


class ParserInternalLinks:
    def __init__(self, text: str) -> None:
        self.text = text
        self.targets: List[InternalLink] = []
        self.parse()

    def find_sub_links(self, string: str) -> List[str]:
        matches: List[str] = []
        length = len(string)
        i = 0
        while i < length - 1:
            if string[i : i + 2] == "[[":
                depth = 1
                j = i + 2
                while j < length - 1 and depth > 0:
                    if string[j : j + 2] == "[[":
                        depth += 1
                        j += 2
                        continue
                    if string[j : j + 2] == "]]":
                        depth -= 1
                        j += 2
                        if depth == 0:
                            matches.append(string[i:j])
                            break
                        continue
                    j += 1
                i = j
                continue
            i += 1
        return matches

    def parse(self) -> None:
        self.targets = []
        for text_link in self.find_sub_links(self.text):
            if text_link.startswith("[[") and text_link.endswith("]]"):
                inner = text_link[2:-2]
                parts = inner.split("|", 1)
                if len(parts) == 1:
                    link = InternalLink(parts[0])
                else:
                    link = InternalLink(parts[0], parts[1])
                self.targets.append(link)

    def getTargets(self) -> List[InternalLink]:
        return list(self.targets)
