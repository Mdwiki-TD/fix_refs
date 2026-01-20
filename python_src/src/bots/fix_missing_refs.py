"""
Fix missing references by expanding from source text
"""

import json
from pathlib import Path
from typing import Dict, List, Any
from ..parsers.citations import get_citations, get_short_citations, get_full_refs, Citation
from ..utils.http import get_url
from ..utils.debug import echo_test, echo_debug


def find_mdwiki_revid(sourcetitle: str, path: str) -> str:
    """Find MDWiki revision ID from JSON file

    Args:
        sourcetitle: Source page title
        path: Path to revisions directory

    Returns:
        MDWiki revision ID or empty string
    """
    json_file = f"{path}/revisions_new/json_data.json"

    if not Path(json_file).exists():
        return ""

    try:
        with open(json_file, 'r', encoding='utf-8') as f:
            data: Dict[str, Any] = json.load(f)  # type: ignore

        echo_test(f"url{json_file}")
        echo_test(f"count of data: {len(data)}")

        result = data.get(sourcetitle, "")
        return str(result) if isinstance(result, str) else ""
    except (json.JSONDecodeError, IOError):
        return ""


def get_full_text(sourcetitle: str, mdwiki_revid: int) -> str:
    """Get full text from MDWiki revision

    Args:
        sourcetitle: Source page title
        mdwiki_revid: MDWiki revision ID

    Returns:
        Full text or empty string
    """
    sourcetitle = sourcetitle.replace(" ", "_")

    path = "I:/medwiki/new/medwiki.toolforge.org_repo/public_html"

    if mdwiki_revid == 0:
        revid_str = find_mdwiki_revid(sourcetitle, path)
        mdwiki_revid = int(revid_str) if revid_str.isdigit() else 0

    if mdwiki_revid == 0:
        echo_test(f"empty mdwiki_revid, sourcetitle:({sourcetitle})")
        return ""

    file = f"{path}/revisions_new/{mdwiki_revid}/wikitext.txt"

    if not Path(file).exists():
        file = f"{Path(__file__).parent.parent.parent.parent}/resources/revisions/{mdwiki_revid}/wikitext.txt"

    echo_test(file)

    if not Path(file).exists():
        echo_test(f"file not found: {file}")
        return ""

    echo_test(f"url{file}")

    try:
        with open(file, 'r', encoding='utf-8') as f:
            return f.read()
    except IOError:
        return ""


def refs_expand(short_refs: List[Citation], text: str, alltext: str) -> str:
    """Expand short references with full text from source

    Args:
        short_refs: List of short citation references
        text: Text to fix
        alltext: Source text with full references

    Returns:
        Text with expanded references
    """
    refs = get_full_refs(alltext)

    for cite in short_refs:
        name = cite.get_name()
        refe = cite.get_original_text()

        rr = refs.get(name, "")

        if rr:
            echo_debug(f"refs_expand: {name}")
            text = text.replace(refe, rr)

    return text


def find_empty_short(text: str) -> Dict[str, Citation]:
    """Find short references that have no corresponding full reference

    Args:
        text: Text to search

    Returns:
        Dictionary mapping reference names to short citations
    """
    shorts = get_short_citations(text)
    fulls = get_full_refs(text)
    empty_refs: Dict[str, Citation] = {}

    for cite in shorts:
        name = cite.get_name()

        if name not in fulls:
            empty_refs[name] = cite

    return empty_refs


def fix_missing_refs(text: str, sourcetitle: str, mdwiki_revid: int = 0) -> str:
    """Fix missing references by expanding from MDWiki source

    Args:
        text: Text to fix
        sourcetitle: Source page title
        mdwiki_revid: MDWiki revision ID

    Returns:
        Text with missing references expanded
    """
    empty_short = find_empty_short(text)

    echo_debug(f"empty refs: {len(empty_short)}")

    if not empty_short:
        return text

    full_text = get_full_text(sourcetitle, mdwiki_revid)

    if not full_text:
        return text

    text = refs_expand(list(empty_short.values()), text, full_text)

    return text
