"""Python port of ``src/WikiParse/src/DataModel/Parameters.php``."""

from __future__ import annotations

from typing import Dict, Iterable, MutableMapping, Union

ParameterInput = Union[MutableMapping, Iterable]


class Parameters:
    """Container that mimics PHP's associative array behaviour for parameters."""

    def __init__(self, parameters: ParameterInput = None) -> None:
        if parameters is None:
            self.parameters: Dict[Union[str, int], str] = {}
        elif isinstance(parameters, dict):
            self.parameters = dict(parameters)
        else:
            self.parameters = dict(parameters)

    def getParameters(self):
        return dict(self.parameters)

    def delete(self, key) -> None:
        self.parameters.pop(key, None)

    def get(self, key, default: str = "") -> str:
        return self.parameters.get(key, default)

    def has(self, key) -> bool:
        return key in self.parameters

    def set(self, key, value: str) -> None:
        self.parameters[key] = value

    def changeParametersNames(self, mapping) -> None:
        new_parameters: Dict[Union[str, int], str] = {}
        for key, value in self.parameters.items():
            if key in mapping:
                new_key = mapping[key]
                new_parameters[new_key] = value
            else:
                new_parameters[key] = value
        self.parameters = new_parameters

    def changeParameterName(self, old, new) -> None:
        self.changeParametersNames({old: new})

    def str_pad_right(self, string: str, length: int, pad: str = " ", encoding: str = "UTF-8") -> str:  # noqa: ARG002
        diff = length - len(string)
        return string + pad * diff if diff > 0 else string

    def toString(self, ljust: int = 0, newLine: bool = False) -> str:
        separator = "\n" if newLine else ""
        result = ""
        index = 1
        for key, value in self.parameters.items():
            formatted_value = value.strip() if newLine else value
            is_positional = False
            if isinstance(key, int) and index == key:
                is_positional = True
            else:
                try:
                    if int(key) == index:
                        is_positional = True
                except (TypeError, ValueError):
                    is_positional = False

            if is_positional:
                result += f"|{formatted_value}"
            else:
                formatted_key = self.str_pad_right(str(key), ljust) if ljust > 0 else str(key)
                result += f"{separator}|{formatted_key}={formatted_value}"
            index += 1

        return result.strip()

    def __toString(self) -> str:  # pragma: no cover
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
