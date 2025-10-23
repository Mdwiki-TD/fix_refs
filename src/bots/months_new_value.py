"""Date helpers translated from ``src/bots/months_new_value.php``."""

from __future__ import annotations

import re
from typing import Dict


_MONTHS_TRANSLATIONS: Dict[str, Dict[str, str]] = {
    "pt": {
        "january": "janeiro",
        "february": "fevereiro",
        "march": "março",
        "april": "abril",
        "may": "maio",
        "june": "junho",
        "july": "julho",
        "august": "agosto",
        "september": "setembro",
        "october": "outubro",
        "november": "novembro",
        "december": "dezembro",
    },
    "es": {
        "january": "enero",
        "february": "febrero",
        "march": "marzo",
        "april": "abril",
        "may": "mayo",
        "june": "junio",
        "july": "julio",
        "august": "agosto",
        "september": "septiembre",
        "october": "octubre",
        "november": "noviembre",
        "december": "diciembre",
    },
}

_DATE_PATTERNS = [
    re.compile(r"^(?:(?P<d>\d{1,2})\s+)?(?P<m>January|February|March|April|May|June|July|August|September|October|November|December),?\s+(?P<y>\d{4})$", re.IGNORECASE),
    re.compile(r"^(?P<m>January|February|March|April|May|June|July|August|September|October|November|December)\s+(?P<d>\d{1,2}),?\s+(?P<y>\d{4})$", re.IGNORECASE),
]


def new_date(value: str, lang: str = "pt") -> str:
    mapping = _MONTHS_TRANSLATIONS.get(lang)
    if not mapping:
        return value.strip()

    value = value.strip()
    for pattern in _DATE_PATTERNS:
        match = pattern.match(value)
        if not match:
            continue
        day = (match.group("d") or "").strip()
        month_key = match.group("m").lower()
        year = match.group("y")
        translated = mapping.get(month_key)
        if not translated:
            continue
        if lang == "es":
            if day:
                return f"{day} de {translated} de {year}".strip()
            return f"{translated} de {year}".strip()
        if day:
            return f"{day} de {translated} {year}".strip()
        return f"{translated} {year}".strip()
    return value


def make_date_new_val_pt(value: str) -> str:
    return new_date(value, "pt")


def make_date_new_val_es(value: str) -> str:
    return new_date(value, "es")


__all__ = ["make_date_new_val_pt", "make_date_new_val_es", "new_date"]
