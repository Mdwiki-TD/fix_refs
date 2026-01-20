"""
Add English language parameter to references
"""

import re
from typing import Dict
from ..parsers.citations import get_citations, Citation
from ..parsers.template import get_templates, Template
from ..utils.debug import echo_debug


def add_lang_en(text: str) -> str:
    """Add language=en to citation templates that don't have language parameter

    Args:
        text: Text containing citations

    Returns:
        Text with language parameter added
    """
    refs_pattern = r'(?is)(?P<pap><ref[^>\/]*>)(?P<ref>.*?<\/ref>)'
    matches = re.findall(refs_pattern, text)

    for pap, ref in matches:
        if not ref.strip():
            continue

        if re.search(r'\|\s*language\s*\=\s*\w+', ref):
            continue

        ref2 = re.sub(r'(\|\s*language\s*\=\s*)(\|\}\})', r'\1en\2', ref)

        if ref2 == ref:
            ref2 = ref.replace('}}</ref>', '|language=en}}</ref>')

        if ref2 != ref:
            text = text.replace(pap + ref, pap + ref2)

    return text


def add_lang_en_new(temp_text: str) -> str:
    """Add language parameter to templates

    Args:
        temp_text: Text containing templates

    Returns:
        Text with language parameter added
    """
    new_text = temp_text
    temps = get_templates(temp_text.strip())

    for temp in temps:
        temp_old = temp.get_original_text()
        language = temp.get("language", "")

        if language == "":
            temp.set("language", "en")
            temp_new = temp.to_string()
            new_text = new_text.replace(temp_old, temp_new)

    return new_text


def add_lang_en_to_refs(text: str) -> str:
    """Add English language parameter to all references in text

    Args:
        text: Text containing references

    Returns:
        Text with language parameter added to references
    """
    echo_debug("\n add_lang_en_to_refs:\n")
    new_text = text
    citations = get_citations(text)

    for citation in citations:
        cite_temp = citation.get_content()
        new_temp = add_lang_en_new(cite_temp)
        new_text = new_text.replace(cite_temp, new_temp)

    return new_text
