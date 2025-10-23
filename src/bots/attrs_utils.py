"""Utilities for parsing ``<ref>`` attributes.

This module mirrors ``src/bots/attrs_utils.php`` and exposes the same
function names with Python implementations.
"""

from __future__ import annotations

import re
from typing import Dict

_ATTRFIND_TOLERANT = re.compile(
    r"((?<=['\"\s/])[^\s/>][^\s/=>]*)(\s*=+\s*('([^']*)'|\"([^\"]*)\"|(?!['\"]).*?))?(?:\s|/(?!>))*",
    re.UNICODE,
)


def _parse(text: str) -> Dict[str, str]:
    attributes: Dict[str, str] = {}
    for match in _ATTRFIND_TOLERANT.finditer(text):
        name = match.group(1)
        if not name:
            continue
        value_group = match.group(3) or ""
        attributes[name.lower()] = value_group
    return attributes


def parseAttributes(text: str) -> Dict[str, str]:
    """Return attribute mapping for the string between ``<ref`` and ``>``."""

    snippet = f"<ref {text}>"
    return _parse(snippet)


def get_attrs(text: str) -> Dict[str, str]:
    """Alias kept for backwards compatibility with the PHP code."""

    snippet = f"<ref {text}>"
    return _parse(snippet)


__all__ = ["parseAttributes", "get_attrs"]
