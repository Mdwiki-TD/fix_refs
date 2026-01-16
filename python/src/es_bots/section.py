"""
Spanish section module

Usage:
    from src.es_bots.section import es_section
"""

import re


def es_section(sourcetitle, text, mdwiki_revid):
    """
    Add Spanish translation section
    
    Args:
        sourcetitle: Source title
        text: Text to process
        mdwiki_revid: MDWiki revision ID
        
    Returns:
        Modified text
    """
    # Replace old template with new one
    text = re.sub(
        r'\{\{\s*Traducido\s*ref\s*\|\s*mdwiki\s*\|',
        "{{Traducido ref MDWiki|en|",
        text,
        flags=re.IGNORECASE
    )
    
    # If template already exists (any variant), return as-is
    if re.search(r'\{\{\s*Traducido\s*ref(?:\s*MDWiki)?\s*\|', text, re.IGNORECASE):
        return text
    
    date = "{{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}"
    temp = f"{{{{Traducido ref MDWiki|en|{sourcetitle}|oldid={mdwiki_revid}|trad=|fecha={date}}}}}"
    
    # Insert after "== Enlaces externos ==" if it exists, otherwise append
    if re.search(r'==\s*Enlaces\s*externos\s*==', text, re.IGNORECASE):
        text = re.sub(
            r'(==\s*Enlaces\s*externos\s*==)',
            rf'\1\n{temp}\n',
            text,
            count=1,
            flags=re.IGNORECASE
        )
    else:
        text += f"\n== Enlaces externos ==\n{temp}\n"
    
    return text
