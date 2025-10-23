"""Python translation of ``work.php``."""

from __future__ import annotations

import json
import os
from pathlib import Path
from urllib.error import URLError
from urllib.request import Request, urlopen

from .src import include_files
from .src.index import fix_page
from .TestBot import echo_test


include_files.include_all()

USER_AGENT = "WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)"


def get_curl(url: str) -> str:
    request = Request(url, headers={"User-Agent": USER_AGENT})
    try:
        with urlopen(request, timeout=5) as response:  # nosec: B310
            return response.read().decode("utf-8")
    except URLError as exc:  # pragma: no cover - network failures
        echo_test(f"\ncURL Error: {exc}\n{url}")
        return ""


def json_load_file(filename: str):
    path = Path(filename)
    if not path.exists():
        return None
    try:
        return json.loads(path.read_text(encoding="utf-8"))
    except json.JSONDecodeError:
        return None


def load_settings_new() -> dict:
    server_name = os.environ.get("SERVER_NAME", "")
    if server_name == "mdwiki.toolforge.org":
        json_data = get_curl("https://mdwiki.toolforge.org/api.php?get=language_settings")
    else:
        json_data = get_curl("http://localhost:9001/api.php?get=language_settings")
    try:
        data = json.loads(json_data)
    except json.JSONDecodeError:
        data = {}
    results = data.get("results", [])
    new = {}
    for value in results:
        lang_code = value.get("lang_code")
        if lang_code:
            new[lang_code] = value
    return new


def fix_page_here(text: str, title: str, langcode: str, sourcetitle: str, mdwiki_revid) -> str:
    setting = load_settings_new()
    lang_default = setting.get(langcode, {})
    move_dots = bool(int(lang_default.get("move_dots", 0)))
    expand = bool(int(lang_default.get("expend", 1))) or True
    add_en_lang = bool(int(lang_default.get("add_en_lang", 0)))
    return fix_page(text, title, move_dots, expand, add_en_lang, langcode, sourcetitle, mdwiki_revid)


def DoChangesToText1(sourcetitle: str, title: str, text: str, lang: str, mdwiki_revid) -> str:
    newtext = fix_page_here(text, title, lang, sourcetitle, mdwiki_revid)
    return newtext if newtext else text
