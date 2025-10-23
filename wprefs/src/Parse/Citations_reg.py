"""Python port of ``src/Parse/Citations_reg.php``."""

from __future__ import annotations

import re
from typing import Dict, List


def get_name(options: str) -> str:
    if not options or options.strip() == "":
        return ""

    pattern = re.compile(r"name\s*=\s*[\"']*([^>\"']*)[\"']*\s*", re.IGNORECASE | re.UNICODE)
    match = pattern.search(options)
    if not match:
        return ""
    return match.group(1).strip()


def get_Reg_Citations(text: str) -> List[Dict[str, str]]:
    pattern = re.compile(r"<ref([^/>]*?)>(.+?)</ref>", re.IGNORECASE | re.DOTALL)
    citations: List[Dict[str, str]] = []
    for match in pattern.finditer(text):
        options = match.group(1)
        citation = {
            "content": match.group(2),
            "tag": match.group(0),
            "name": get_name(options),
            "options": options,
        }
        citations.append(citation)
    return citations


def get_full_refs(text: str) -> Dict[str, str]:
    full: Dict[str, str] = {}
    for citation in get_Reg_Citations(text):
        full[citation["name"]] = citation["tag"]
    return full


def getShortCitations(text: str) -> List[Dict[str, str]]:
    pattern = re.compile(r"<ref ([^/>]*?)/\s*>", re.IGNORECASE | re.DOTALL)
    citations: List[Dict[str, str]] = []
    for match in pattern.finditer(text):
        options = match.group(1)
        citation = {
            "content": "",
            "tag": match.group(0),
            "name": get_name(options),
            "options": options,
        }
        citations.append(citation)
    return citations
