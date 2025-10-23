"""Spanish reference relocation helpers."""

from __future__ import annotations

import re
from typing import Dict

from ..Parse import Citations, Citations_reg
from ..WikiParse import Template as template_helpers


def get_refs(text: str) -> Dict[str, Dict[str, str]]:
    refs: Dict[str, str] = {}
    new_text = text
    citations = Citations.getCitationsOld(text)
    counter = 0
    for citation in citations:
        cite_text = citation.getOriginalText()
        cite_contents = citation.getContent()
        cite_attrs = (citation.getAttributes() or "").strip()
        if not cite_attrs:
            counter += 1
            cite_attrs = f"name='autogen_{counter}'"
        refs[cite_attrs] = cite_contents
        cite_newtext = f"<ref {cite_attrs} />"
        new_text = new_text.replace(cite_text, cite_newtext)
    return {"refs": refs, "new_text": new_text}


def check_short_refs(line: str) -> str:
    shorts = Citations_reg.getShortCitations(line)
    for short in shorts:
        line = line.replace(short.get("tag", ""), "")
    return re.sub(r"\n+", "\n", line)


def make_line(refs: Dict[str, str]) -> str:
    lines = []
    for name, ref in refs.items():
        lines.append(f"<ref {name.strip()}>{ref}</ref>")
    return "\n".join(lines)


def add_line_to_temp(line: str, text: str) -> str:
    temps_in = template_helpers.getTemplates(text)
    new_text = text
    temp_already_in = False
    for temp in temps_in:
        name = temp.getStripName().lower()
        if name not in {"reflist", "listaref"}:
            continue
        old_text_template = temp.getOriginalText()
        refn_param = temp.getParameter("refs")
        if refn_param:
            refn_param = check_short_refs(refn_param)
            line = f"{refn_param.strip()}\n{line.strip()}"
        temp.setParameter("refs", f"\n{line.strip()}\n")
        temp_already_in = True
        new_text_str = temp.toString()
        new_text = new_text.replace(old_text_template, new_text_str)
        break
    if not temp_already_in:
        section_ref = f"\n== Referencias ==\n{{{{listaref|refs=\n{line}\n}}}}"
        new_text += section_ref
    return new_text


def mv_es_refs(text: str) -> str:
    if not text:
        return text
    refs_info = get_refs(text)
    new_lines = make_line(refs_info["refs"])
    new_text = refs_info["new_text"]
    return add_line_to_temp(new_lines, new_text)


__all__ = ["mv_es_refs"]
