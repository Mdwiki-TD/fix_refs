"""Python port of ``src/WikiParse/src/ParserTemplates.php``."""

from __future__ import annotations

from typing import Dict, List

from .DataModel.Template import Template
from .ParserTemplate import ParserTemplate


class ParserTemplates:
    def __init__(self, text: str) -> None:
        self.text = text
        self.templates: List[Template] = []
        self.maxDepth = 10
        self.parse()

    def find_sub_templates(self, string: str) -> List[Dict[str, str]]:
        matches: List[Dict[str, str]] = []
        length = len(string)
        i = 0
        while i < length - 1:
            if string[i : i + 2] == "{{":
                depth = 1
                j = i + 2
                while j < length - 1 and depth > 0:
                    if string[j : j + 2] == "{{":
                        depth += 1
                        j += 2
                        continue
                    if string[j : j + 2] == "}}":
                        depth -= 1
                        j += 2
                        if depth == 0:
                            full = string[i:j]
                            inner = full[2:-2]
                            matches.append({"full": full, "inner": inner})
                            break
                        continue
                    j += 1
                i = j
                continue
            i += 1
        return matches

    def parse(self) -> None:
        stack: List[Dict[str, object]] = [{"text": self.text, "depth": 0}]
        while stack:
            current = stack.pop()
            current_text = current["text"]
            current_depth = current["depth"]
            if current_depth >= self.maxDepth:
                continue

            for match in self.find_sub_templates(str(current_text)):
                template_full = match["full"]
                template_inner = match["inner"]
                parser = ParserTemplate(template_full)
                self.templates.append(parser.getTemplate())
                stack.append({"text": template_inner, "depth": current_depth + 1})

    def getTemplates(self, name: str | None = None) -> List[Template]:
        if not name:
            return list(self.templates)
        return [template for template in self.templates if template.getName() == name]
