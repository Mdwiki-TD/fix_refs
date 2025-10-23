"""Python port of ``src/WikiParse/src/DataModel/Tag.php``."""

from __future__ import annotations

from .Attribute import Attribute


class Tag:
    """Represents a generic tag such as ``<ref>``."""

    def __init__(
        self,
        tagname: str,
        content: str,
        attributes: str = "",
        originalText: str = "",
        selfClosing: bool = False,
    ) -> None:
        self.tagname = tagname
        self.content = content
        self.attributes = attributes
        self.originalText = originalText
        self.selfClosing = selfClosing
        self.attrs = Attribute(self.attributes)

    def getName(self) -> str:
        return self.tagname

    def getOriginalText(self) -> str:
        return self.originalText

    def getContent(self) -> str:
        return self.content

    def setContent(self, content: str) -> None:
        self.content = content

    def getAttributes(self) -> str:
        return self.attributes

    def setAttributes(self, attributes: str) -> None:
        self.attributes = attributes
        self.attrs = Attribute(self.attributes)

    def Attrs(self) -> Attribute:
        return self.attrs

    def toString(self) -> str:
        attrs_str = self.attrs.toString()
        attrs_trimmed = attrs_str.strip()
        space = " " if attrs_trimmed else ""
        if self.selfClosing and not self.content.strip():
            return f"<{self.tagname}{attrs_trimmed}/>"
        return f"<{self.tagname}{space}{attrs_trimmed}>{self.content}</{self.tagname.strip()}>"

    def __toString(self) -> str:  # pragma: no cover
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
