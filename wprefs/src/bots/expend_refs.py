"""Reference expansion helpers mirroring ``src/bots/expend_refs.php``."""

from __future__ import annotations

from typing import Dict, Iterable, List

from ..Parse import Citations_reg as reg_citations


def _short_refs(first: str) -> Iterable[Dict[str, str]]:
    return reg_citations.getShortCitations(first)


def _full_refs(text: str) -> Dict[str, str]:
    return reg_citations.get_full_refs(text)


def refs_expend_work(first: str, alltext: str | None = None) -> str:
    """Replace shortened ``<ref name="..."/>`` tags with their full form."""

    source = alltext or first
    refs = _full_refs(source)
    short_refs: List[Dict[str, str]] = list(_short_refs(first))

    for citation in short_refs:
        name = citation.get("name")
        tag = citation.get("tag")
        if not name or not tag:
            continue
        replacement = refs.get(name)
        if replacement:
            first = first.replace(tag, replacement)
    return first


__all__ = ["refs_expend_work"]
