"""
Add English language parameter to references
"""

import wikitextparser as wtp
from ..parsers.citations import get_citations
from ..utils.debug import echo_debug


def add_lang_en_new(temp_text: str) -> str:
    """Add language parameter to templates

    Args:
        temp_text: Text containing templates

    Returns:
        Text with language parameter added
    """
    wikicode = wtp.parse(temp_text.strip())

    for wt_template in wikicode.templates:
        language_arg = wt_template.get_arg('language')
        if not language_arg or not language_arg.value.strip():
            wt_template.set_arg('language', 'en')

    new_text = wikicode.string
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
