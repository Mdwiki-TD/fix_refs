"""Python port of ``src/WikiParse/src/DataModel/ExternalLink.php``."""


class ExternalLink:
    def __init__(self, link: str, text: str = "") -> None:
        self.link = link
        self.text = text

    def getText(self) -> str:
        return self.text

    def getLink(self) -> str:
        return self.link

    def toString(self) -> str:
        if self.text == self.link:
            return f"[{self.link}]"
        return f"[{self.link} {self.text}]"

    def __toString(self) -> str:  # pragma: no cover
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
