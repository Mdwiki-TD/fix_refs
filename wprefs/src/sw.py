"""Swahili specific fixes translated from PHP."""

from __future__ import annotations

import re


def sw_fixes(text: str) -> str:
    return re.sub(r"(=+)\s*Marejeleo\s*(\1)", r"\1 Marejeo \1", text, flags=re.IGNORECASE)


__all__ = ["sw_fixes"]
