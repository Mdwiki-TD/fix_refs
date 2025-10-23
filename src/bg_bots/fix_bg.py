"""Bulgarian specific fixes translated from PHP."""

from __future__ import annotations

import re


def bg_section(text: str, sourcetitle: str, mdwiki_revid: str | int | None) -> str:
    if re.search(r"\{\{\s*Превод\s*от\s*\|", text, flags=re.IGNORECASE):
        return text
    temp = f"{{{{Превод от|mdwiki|{sourcetitle}|{mdwiki_revid}}}}}\n"
    match = re.search(r"\[\[(Категория|Category):", text, flags=re.IGNORECASE)
    if match:
        pos = match.start()
        return text[:pos] + temp + text[pos:]
    return text + "\n" + temp


def bg_fixes(text: str, sourcetitle: str, mdwiki_revid: str | int | None) -> str:
    text = bg_section(text, sourcetitle, mdwiki_revid)
    text = re.sub(r"\[\[\s*(Категория|Category)\s*:\s*Translated from MDWiki\s*\]\]", "", text, flags=re.IGNORECASE)
    return text


__all__ = ["bg_fixes", "bg_section"]
