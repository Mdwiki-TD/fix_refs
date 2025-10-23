"""Python port of ``src/WikiParse/src/DataModel/InternalLink.php``."""


class InternalLink:
    def __init__(self, target: str, text: str = "") -> None:
        self.target = target
        self.text = text if text else target

    def getText(self) -> str:
        return self.text

    def getTarget(self) -> str:
        return self.target

    def toString(self) -> str:
        if self.text == self.target:
            return f"[[{self.target}]]"
        return f"[[{self.target}|{self.text}]]"

    def __toString(self) -> str:  # pragma: no cover
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
