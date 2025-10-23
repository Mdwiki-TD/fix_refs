"""Python port of ``src/WikiParse/src/DataModel/Citation.php``."""


class Citation:
    def __init__(self, text: str, options: str = "", cite_text: str = "") -> None:
        self.text = text
        self.options = options
        self.cite_text = cite_text

    def getOriginalText(self) -> str:
        return self.cite_text

    def getTemplate(self) -> str:
        return self.text

    def getContent(self) -> str:
        return self.text

    def getAttributes(self) -> str:
        return self.options

    def toString(self) -> str:
        return f"<ref {self.options.strip()}>{self.text}</ref>"

    def __toString(self) -> str:  # pragma: no cover
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
