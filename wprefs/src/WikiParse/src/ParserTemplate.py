"""Python port of ``src/WikiParse/src/ParserTemplate.php``."""

from __future__ import annotations

from typing import Dict, List

from .DataModel.Template import Template


class ParserTemplate:
    def __init__(self, templateText: str) -> None:
        self.templateText = templateText.strip()
        self.name: str = ""
        self.parameters: Dict[int | str, str] = {}
        self.pipe = "|"
        self.pipeR = "-_-"
        self.parse()

    def _replace_nested_pipes(self, content: str) -> str:
        result: List[str] = []
        depth_template = 0
        depth_link = 0
        i = 0
        length = len(content)
        while i < length:
            two = content[i : i + 2]
            if two == "{{":
                depth_template += 1
                result.append(two)
                i += 2
                continue
            if two == "}}" and depth_template > 0:
                depth_template -= 1
                result.append(two)
                i += 2
                continue
            if two == "[[":
                depth_link += 1
                result.append(two)
                i += 2
                continue
            if two == "]]" and depth_link > 0:
                depth_link -= 1
                result.append(two)
                i += 2
                continue
            if content[i] == "|" and (depth_template > 0 or depth_link > 0):
                result.append(self.pipeR)
                i += 1
                continue
            result.append(content[i])
            i += 1
        return "".join(result)

    def _split_params(self, content: str) -> List[str]:
        parts: List[str] = []
        current: List[str] = []
        depth_template = 0
        depth_link = 0
        i = 0
        length = len(content)
        while i < length:
            two = content[i : i + 2]
            if two == "{{":
                depth_template += 1
                current.append(two)
                i += 2
                continue
            if two == "}}" and depth_template > 0:
                depth_template -= 1
                current.append(two)
                i += 2
                continue
            if two == "[[":
                depth_link += 1
                current.append(two)
                i += 2
                continue
            if two == "]]" and depth_link > 0:
                depth_link -= 1
                current.append(two)
                i += 2
                continue
            char = content[i]
            if char == "|" and depth_template == 0 and depth_link == 0:
                parts.append("".join(current))
                current = []
                i += 1
                continue
            current.append(char)
            i += 1
        parts.append("".join(current))
        return parts

    def parse(self) -> None:
        text = self.templateText
        if not (text.startswith("{{") and text.endswith("}}")):
            return

        inner = text[2:-2]
        sanitized = self._replace_nested_pipes(inner)
        params = [segment.replace(self.pipeR, self.pipe) for segment in self._split_params(sanitized)]
        if not params:
            return

        self.name = params[0]
        data: Dict[int | str, str] = {}
        for index, param in enumerate(params[1:], start=1):
            if "=" in param:
                key, value = param.split("=", 1)
                data[key.strip()] = value.strip()
            else:
                data[index] = param
        self.parameters = data

    def getTemplate(self) -> Template:
        return Template(self.name, self.parameters, self.templateText)
