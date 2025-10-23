"""Duplicate reference removal translated from PHP."""

from __future__ import annotations

from typing import Dict

from ..Parse import Citations
from .attrs_utils import get_attrs
from .refs_utils import remove_start_end_quotes
from ..TestBot import echo_debug


def fix_refs_names(text: str) -> str:
    new_text = text
    citations = Citations.getCitationsOld(text)

    for citation in citations:
        attrs = citation.getAttributes() or ""
        attrs = attrs.strip()
        if not attrs:
            continue
        original = f"<ref {attrs}>"
        if original not in new_text:
            continue
        parsed_attrs = get_attrs(attrs)
        if not parsed_attrs:
            continue
        rebuilt = " ".join(f"{name}={remove_start_end_quotes(value)}" for name, value in parsed_attrs.items())
        rebuilt = rebuilt.strip()
        replacement = f"<ref {rebuilt}>"
        new_text = new_text.replace(original, replacement)
    return new_text


def remove_Duplicate_refs_With_attrs(text: str) -> str:
    new_text = text
    refs_to_check: Dict[str, str] = {}
    refs: Dict[str, str] = {}
    citations = Citations.getCitationsOld(new_text)
    counter = 0

    for citation in citations:
        full_text = citation.getOriginalText()
        attrs = (citation.getAttributes() or "").strip()
        if not attrs:
            counter += 1
            name = f"autogen_{counter}"
            attrs = f"name='{name}'"
        echo_debug(f"\ncite_attrs: (({attrs}))")
        short_form = f"<ref {attrs} />"
        if attrs in refs:
            new_text = new_text.replace(full_text, short_form)
        else:
            refs_to_check[short_form] = full_text
            refs[attrs] = short_form

    for short_form, original in refs_to_check.items():
        if original not in new_text:
            new_text = new_text.replace(short_form, original, 1)

    return new_text


__all__ = ["remove_Duplicate_refs_With_attrs", "fix_refs_names"]
