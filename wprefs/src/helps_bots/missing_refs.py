"""Fill in missing reference bodies from MDWiki backups."""

from __future__ import annotations

import json
import os
from pathlib import Path
from typing import Dict

from ..MdCat import get_url_curl
from ..Parse import Citations_reg as reg_citations
from ..TestBot import echo_debug, echo_test


def _server_name() -> str:
    return os.environ.get("SERVER_NAME", "localhost")


def _toolforge_base() -> str:
    return "http://localhost:9001" if _server_name() == "localhost" else "https://mdwikicx.toolforge.org/"


def get_full_text_url(sourcetitle: str, mdwiki_revid: str | int | None) -> str:
    base = _toolforge_base()
    mdwiki_revid = str(mdwiki_revid or "").strip()
    if not mdwiki_revid or mdwiki_revid == "0":
        json_file = f"{base}/revisions_new/json_data.json"
        data_raw = get_url_curl(json_file)
        try:
            data = json.loads(data_raw or "{}")
        except json.JSONDecodeError:
            data = {}
        echo_test(f"url{json_file}")
        echo_test(f"count of data: {len(data)}")
        key = sourcetitle.replace(" ", "_")
        mdwiki_revid = str(data.get(key, ""))
    if not mdwiki_revid:
        echo_test("empty mdwiki_revid")
        return ""
    file_url = f"{base}/revisions_new/{mdwiki_revid}/wikitext.txt"
    echo_test(file_url)
    text = get_url_curl(file_url)
    if not text:
        echo_test(f"Failed to fetch URL: {file_url}")
        return ""
    return text


def get_full_text(sourcetitle: str, mdwiki_revid: str | int | None) -> str:
    sourcetitle = sourcetitle.replace(" ", "_")
    base = Path("I:/medwiki/new/medwiki.toolforge.org_repo/public_html")
    if _server_name() != "localhost":
        base = Path("/data/project/mdwikicx/public_html")
    mdwiki_revid = str(mdwiki_revid or "").strip()
    if not mdwiki_revid or mdwiki_revid == "0":
        json_file = base / "revisions_new" / "json_data.json"
        try:
            data = json.loads(json_file.read_text(encoding="utf-8"))
        except (FileNotFoundError, json.JSONDecodeError):
            data = {}
        echo_test(f"url{json_file}")
        echo_test(f"count of data: {len(data)}")
        mdwiki_revid = str(data.get(sourcetitle, ""))
    if not mdwiki_revid:
        echo_test(f"empty mdwiki_revid, sourcetitle:({sourcetitle})")
        return ""
    file_path = base / "revisions_new" / mdwiki_revid / "wikitext.txt"
    echo_test(str(file_path))
    if not file_path.exists():
        echo_test(f"file not found: {file_path}")
        return ""
    echo_test(f"url{file_path}")
    return file_path.read_text(encoding="utf-8")


def refs_expend(short_refs: Dict[str, Dict[str, str]], text: str, alltext: str) -> str:
    refs = reg_citations.get_full_refs(alltext)
    for cite in short_refs.values():
        name = cite.get("name")
        tag = cite.get("tag")
        if not name or not tag:
            continue
        replacement = refs.get(name)
        if replacement:
            echo_debug(f"refs_expend: {name}")
            text = text.replace(tag, replacement)
    return text


def find_empty_short(text: str) -> Dict[str, Dict[str, str]]:
    shorts = reg_citations.getShortCitations(text)
    fulls = reg_citations.get_full_refs(text)
    empty_refs: Dict[str, Dict[str, str]] = {}
    for cite in shorts:
        name = cite.get("name")
        if not name:
            continue
        if not fulls.get(name):
            empty_refs[name] = cite
    return empty_refs


def fix_missing_refs(text: str, sourcetitle: str, mdwiki_revid: str | int | None) -> str:
    empty_short = find_empty_short(text)
    echo_debug(f"empty refs: {len(empty_short)}")
    if not empty_short:
        return text
    full_text = get_full_text(sourcetitle, mdwiki_revid)
    if not full_text:
        return text
    return refs_expend(empty_short, text, full_text)


__all__ = [
    "fix_missing_refs",
    "find_empty_short",
    "get_full_text",
    "get_full_text_url",
    "refs_expend",
]
