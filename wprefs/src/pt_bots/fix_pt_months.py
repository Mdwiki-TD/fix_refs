"""Portuguese reference helpers translated from PHP."""

from __future__ import annotations

import re

from ..bots import months_new_value
from ..Parse import Citations
from ..WikiParse import Template as template_helpers


def start_end(cite_temp: str) -> bool:
    return cite_temp.startswith("{{") and cite_temp.endswith("}}")


def rm_ref_spaces(newtext: str) -> str:
    dot = r"(\.|,|。|।)"
    regline = r"((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)"
    pattern = re.compile(rf"\s*{dot}\s*{regline}", re.MULTILINE)
    return pattern.sub(r"\1\2", newtext)


def fix_pt_months_in_texts(temp_text: str) -> str:
    new_text = temp_text
    temp_text = temp_text.strip()
    temps = template_helpers.getTemplates(temp_text)
    for temp in temps:
        temp_old = temp.getOriginalText()
        params = temp.parameters
        for key, value in list(params.getParameters().items()):
            new_value = months_new_value.make_date_new_val_pt(value)
            if new_value is not None and new_value.strip() != str(value).strip():
                params.set(key, new_value)
        temp_new = temp.toString()
        new_text = new_text.replace(temp_old, temp_new)
    return new_text


def fix_pt_months_in_refs(text: str) -> str:
    new_text = text
    citations = Citations.getCitationsOld(text)
    for citation in citations:
        cite_temp = citation.getContent()
        new_temp = fix_pt_months_in_texts(cite_temp)
        new_text = new_text.replace(cite_temp, new_temp)
    return new_text


def pt_fixes(text: str) -> str:
    text = fix_pt_months_in_refs(text)
    text = rm_ref_spaces(text)
    return text


__all__ = ["pt_fixes", "fix_pt_months_in_refs", "fix_pt_months_in_texts", "rm_ref_spaces"]
