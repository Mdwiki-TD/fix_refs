"""
Spanish refs module

Implemented from: src/es_bots/es_refs.php

Usage:
    from src.es_bots.es_refs import mv_es_refs
"""

from src.WikiParse.Template import getTemplates
from src.Parse.Citations_reg import getShortCitations
from src.Parse.Citations import getCitationsOld


def get_refs(text: str) -> dict:
    """
    Get references from text and convert to short form
    
    Args:
        text: Text to process
        
    Returns:
        Dictionary with 'refs' and 'new_text' keys
    """
    refs = {}
    citations = getCitationsOld(text)
    new_text = text
    numb = 0
    
    for citation in citations:
        cite_text = citation.getOriginalText()
        cite_contents = citation.getContent()
        cite_attrs = citation.getAttributes()
        cite_attrs = cite_attrs.strip() if cite_attrs else ""
        
        if not cite_attrs:
            numb += 1
            name = f"autogen_{numb}"
            cite_attrs = f"name='{name}'"
        
        refs[cite_attrs] = cite_contents
        cite_newtext = f"<ref {cite_attrs} />"
        new_text = new_text.replace(cite_text, cite_newtext)
    
    return {
        "refs": refs,
        "new_text": new_text
    }


def check_short_refs(line: str) -> str:
    """
    Remove short refs from line
    
    Args:
        line: Line to process
        
    Returns:
        Line with short refs removed
    """
    shorts = getShortCitations(line)
    
    for short in shorts:
        line = line.replace(short["tag"], "")
    
    # Remove multiple newlines
    import re
    line = re.sub(r'\n+', '\n', line)
    
    return line


def make_line(refs: dict) -> str:
    """
    Make reference list from refs dictionary
    
    Args:
        refs: Dictionary of references
        
    Returns:
        Reference list as string
    """
    line = "\n"
    
    for name, ref in refs.items():
        la = f'<ref {name.strip()}>{ref}</ref>\n'
        line += la
    
    return line.strip()


def add_line_to_temp(line: str, text: str) -> str:
    """
    Add reference line to template or create new section
    
    Args:
        line: Reference line to add
        text: Text to modify
        
    Returns:
        Modified text
    """
    temps_in = getTemplates(text)
    new_text = text
    temp_already_in = False
    
    for temp in temps_in:
        name = temp.getStripName()
        old_text_template = temp.getOriginalText()
        
        if name.lower() not in ["reflist", "listaref"]:
            continue
        
        refn_param = temp.getParameter("refs")
        
        if refn_param:
            refn_param = check_short_refs(refn_param)
            line = refn_param.strip() + "\n" + line.strip()
        
        temp.setParameter("refs", "\n" + line.strip() + "\n")
        temp_already_in = True
        new_text_str = temp.toString()
        new_text = new_text.replace(old_text_template, new_text_str)
        break
    
    if not temp_already_in:
        section_ref = f"\n== Referencias ==\n{{{{listaref|refs=\n{line}\n}}}}"
        new_text += section_ref
    
    return new_text


def mv_es_refs(text: str) -> str:
    """
    Move/process Spanish references
    
    Matches: src/es_bots/es_refs.php - mv_es_refs()
    
    Args:
        text: Text to process
        
    Returns:
        Modified text with processed references
    """
    if not text:
        return text
    
    refs = get_refs(text)
    new_lines = make_line(refs['refs'])
    new_text = refs['new_text']
    new_text = add_line_to_temp(new_lines, new_text)
    
    return new_text
