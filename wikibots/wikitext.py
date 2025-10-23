"""Fetch wikitext from Wikipedia using the MediaWiki APIs."""

from __future__ import annotations

import json
from typing import Optional
from urllib.parse import quote, urlencode
from urllib.error import URLError
from urllib.request import Request, urlopen

USER_AGENT = "WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)"


def from_api(title: str, lang: str) -> str:
    url = f"https://{lang}.wikipedia.org/w/api.php"
    data = {
        "action": "query",
        "format": "json",
        "prop": "revisions",
        "rvslots": "*",
        "rvprop": "content",
        "titles": title,
    }
    request = Request(url, data=urlencode(data).encode("utf-8"), headers={"User-Agent": USER_AGENT})
    try:
        with urlopen(request, timeout=5) as response:  # nosec: B310
            payload = response.read().decode("utf-8")
    except URLError:
        return ""
    json_data = json.loads(payload)
    pages = json_data.get("query", {}).get("pages", {})
    for page in pages.values():
        revisions = page.get("revisions", [])
        if not revisions:
            continue
        return revisions[0].get("slots", {}).get("main", {}).get("*", "")
    return ""


def from_rest(title: str, lang: str) -> str:
    safe_title = quote(title, safe="")
    url = f"https://{lang}.wikipedia.org/w/rest.php/v1/page/{safe_title}"
    request = Request(url, headers={"User-Agent": USER_AGENT})
    try:
        with urlopen(request, timeout=5) as response:  # nosec: B310
            payload = response.read().decode("utf-8")
    except URLError:
        return ""
    json_data = json.loads(payload)
    return json_data.get("source", "")


def get_wikipedia_text(title: str, lang: str) -> str:
    text = from_api(title, lang)
    if not text:
        text = from_rest(title, lang)
    return text


__all__ = ["get_wikipedia_text", "from_api", "from_rest"]
