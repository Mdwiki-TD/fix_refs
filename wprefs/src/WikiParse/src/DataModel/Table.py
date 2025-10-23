"""Python port of ``src/WikiParse/src/DataModel/Table.php``."""

from __future__ import annotations

from typing import List, Sequence


class Table:
    def __init__(self, header: Sequence[str], data: Sequence[Sequence[str]], classes: str = "") -> None:
        self.data = [list(row) for row in data]
        self.header = list(header)
        self.classes = classes if classes else "wikitable"

    def getHeaders(self) -> List[str]:
        return list(self.header)

    def getData(self) -> List[List[str]]:
        return [list(row) for row in self.data]

    def get(self, key: str, position: int) -> str:
        if key not in self.header:
            raise ValueError(f'The key "{key}" does not exist in the header.')
        index = self.header.index(key)
        return self.data[position][index]

    def setData(self, key: str, position: int, value: str) -> None:
        if key not in self.header:
            raise ValueError(f'The key "{key}" does not exist in the header.')
        index = self.header.index(key)
        self.data[position][index] = value

    def toString(self) -> str:
        lines = ['{| class="' + self.classes + '"', '|-']
        for header in self.header:
            lines.append('!' + header)
        lines.append('|-')

        for row_index, row in enumerate(self.data):
            cells = []
            for col_index, value in enumerate(row):
                if col_index + 1 == len(self.header):
                    cells.append('|' + value)
                else:
                    cells.append('|' + value + '|')
            lines.append(''.join(cells))
            if row_index + 1 != len(self.data):
                lines.append('|-')

        lines.append('|}')
        return '\n'.join(lines)

    def __toString(self) -> str:  # pragma: no cover
        return self.toString()

    def __str__(self) -> str:
        return self.toString()
