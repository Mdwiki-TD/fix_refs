"""Python port of ``src/WikiParse/src/DataModel/Template.php``."""

from __future__ import annotations

from .Parameters import Parameters


class Template:
    """Represents a wikitext template and its parameters."""

    def __init__(self, name: str, parameters=None, originalText: str = "") -> None:
        self.name = name
        self.nameStrip = name.replace("_", " ").strip()
        self.originalText = originalText
        self.parameters = Parameters(parameters or {})

    def getStripName(self) -> str:
        return self.nameStrip

    def getName(self) -> str:
        return self.name

    def getOriginalText(self) -> str:
        return self.originalText

    def getParameters(self):
        return self.parameters.getParameters()

    def deleteParameter(self, key: str) -> None:
        self.parameters.delete(key)

    def getParameter(self, key: str) -> str:
        return self.parameters.get(key)

    def setName(self, name: str) -> None:
        self.name = name
        self.nameStrip = name.replace("_", " ").strip()

    def setParameter(self, key: str, value: str) -> None:
        self.parameters.set(key, value)

    def changeParameterName(self, old: str, new: str) -> None:
        self.parameters.changeParametersNames({old: new})

    def changeParametersNames(self, params_new) -> None:
        self.parameters.changeParametersNames(params_new)

    def toString(self, newLine: bool = False, ljust: int = 0) -> str:
        separator = "\n" if newLine else ""
        template_name = self.name.strip() if newLine else self.name

        result = f"{{{{{template_name}{separator}"
        params_text = self.parameters.toString(ljust, newLine)
        result += params_text
        result += f"{separator}}}}}"
        return result

    def __toString(self) -> str:  # pragma: no cover
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
