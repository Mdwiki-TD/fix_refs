"""Add ``language=en`` to citations, ported from PHP."""

from __future__ import annotations

import re

from ..Parse import Citations
from ..TestBot import echo_debug
from ..WikiParse import Template as template_helpers


REFS_PATTERN = re.compile(r"(?is)(?P<pap><ref[^>/]*>)(?P<ref>.*?</ref>)")


def add_lang_en(text: str) -> str:
    def repl(match: re.Match[str]) -> str:
        pap = match.group("pap")
        ref = match.group("ref")
        if not ref.strip():
            return pap + ref
        if re.search(r"\|\s*language\s*=\s*\w+", ref, flags=re.UNICODE):
            return pap + ref
        updated = re.sub(r"(\|\s*language\s*=\s*)(\|\}\})", r"\1en\2", ref, flags=re.UNICODE)
        if updated == ref:
            updated = ref.replace("}}</ref>", "|language=en}}</ref>")
        return pap + updated

    return REFS_PATTERN.sub(repl, text)


def add_lang_en_new(temp_text: str) -> str:
    new_text = temp_text
    temp_text = temp_text.strip()
    templates = template_helpers.getTemplates(temp_text)
    for temp in templates:
        temp_old = temp.getOriginalText()
        params = temp.parameters
        language = params.get("language", "") if hasattr(params, "get") else ""
        if language == "":
            params.set("language", "en")  # type: ignore[attr-defined]
            temp_new = temp.toString()
            new_text = new_text.replace(temp_old, temp_new)
    return new_text


def add_lang_en_to_refs(text: str) -> str:
    echo_debug("\n add_lang_en_to_refs:\n")
    new_text = text
    citations = Citations.getCitationsOld(text)
    for citation in citations:
        cite_temp = citation.getContent()
        new_temp = add_lang_en_new(cite_temp)
        new_text = new_text.replace(cite_temp, new_temp)
    return new_text


__all__ = ["add_lang_en", "add_lang_en_to_refs"]
