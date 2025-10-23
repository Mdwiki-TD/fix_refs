"""Redirect helpers translated from PHP."""

from __future__ import annotations

import re


def page_is_redirect(title: str, text: str) -> bool:
    return bool(re.match(r"^#(пренасочване|redirect)", text, flags=re.IGNORECASE))


__all__ = ["page_is_redirect"]
