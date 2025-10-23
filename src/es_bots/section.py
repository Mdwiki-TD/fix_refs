"""Helpers for inserting the Spanish translation template."""

from __future__ import annotations

import re


def es_section(sourcetitle: str, text: str, mdwiki_revid: str | int | None) -> str:
    text = re.sub(r"\{\{\s*Traducido\s*ref\s*\|\s*mdwiki\s*\|", "{{Traducido ref MDWiki|en|", text, flags=re.IGNORECASE)
    if re.search(r"\{\{\s*Traducido\s*ref(?:\s*MDWiki)?\s*\|", text, flags=re.IGNORECASE):
        return text
    date = "{{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}"
    temp = f"{{{{Traducido ref MDWiki|en|{sourcetitle}|oldid={mdwiki_revid}|trad=|fecha={date}}}}}"
    if re.search(r"==\s*Enlaces\s*externos\s*==", text, flags=re.IGNORECASE):
        return re.sub(
            r"(==\s*Enlaces\s*externos\s*==)",
            rf"\1\n{temp}\n",
            text,
            count=1,
            flags=re.IGNORECASE,
        )
    return text + f"\n== Enlaces externos ==\n{temp}\n"


__all__ = ["es_section"]
