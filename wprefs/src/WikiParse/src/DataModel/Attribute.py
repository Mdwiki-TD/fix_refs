"""Python port of ``src/WikiParse/src/DataModel/Attribute.php``."""

from __future__ import annotations

import re
from typing import Dict


class Attribute:
    """Represents the attributes attached to a citation/tag."""

    def __init__(self, content: str) -> None:
        self.content = content
        self.attributes_array: Dict[str, str] = {}
        self.parseAttributes()

    def setContent(self, content: str) -> None:
        self.content = content
        self.parseAttributes()

    def parseAttributes(self) -> None:
        """Parse the raw attribute string into a dictionary."""

        text = f"<ref {self.content}>"
        pattern = re.compile(
            r"""
            ((?<=['"\s/])[^\s/>][^\s/=>]*)           # attribute name
            (\s*=+\s*                                   # equals sign(s)
                (
                    '[^']*'                              # value in single quotes
                    |"[^"]*"                           # value in double quotes
                    |(?!['"])[^>\s]*                   # or an unquoted value
                )
            )?
            (?:\s|/(?!>))*                              # trailing whitespace or solitary slash
            """,
            re.VERBOSE | re.IGNORECASE | re.DOTALL,
        )

        self.attributes_array = {}
        for match in pattern.finditer(text):
            attr_name = match.group(1).lower()
            attr_value = match.group(3) or ""
            self.attributes_array[attr_name] = attr_value

    def getAttributesArray(self) -> Dict[str, str]:
        return dict(self.attributes_array)

    def has(self, key: str) -> bool:
        return key in self.attributes_array

    def get(self, key: str, default: str = "") -> str:
        return self.attributes_array.get(key, default)

    def set(self, key: str, value: str) -> None:
        self.attributes_array[key] = value

    def delete(self, key: str) -> None:
        self.attributes_array.pop(key, None)

    def toString(self, addQuotes: bool = False) -> str:
        parts = []
        for key, value in self.attributes_array.items():
            if not value:
                parts.append(key)
                continue

            formatted_value = value
            if addQuotes:
                if len(formatted_value) > 1:
                    first_char = formatted_value[0]
                    if first_char in {'"', "'"} and formatted_value.endswith(first_char):
                        formatted_value = formatted_value[1:-1]
                formatted_value = f'"{formatted_value}"'

            parts.append(f"{key}={formatted_value}")

        return " ".join(parts)

    def __toString(self) -> str:  # pragma: no cover - PHP compatibility shim
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
