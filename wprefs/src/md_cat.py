"""MDWiki category helpers translated from PHP."""

from __future__ import annotations

import json
import re
from functools import lru_cache
from urllib.error import URLError
from urllib.request import Request, urlopen

from .TestBot import echo_test


USER_AGENT = "WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)"


def get_url_curl(url: str) -> str:
    request = Request(url, headers={"User-Agent": USER_AGENT})
    try:
        with urlopen(request, timeout=5) as response:  # nosec: B310
            return response.read().decode("utf-8")
    except URLError as exc:  # pragma: no cover - network issues
        echo_test(f"\ncURL Error: {exc}\n{url}")
        return ""


@lru_cache(maxsize=1)
def get_cats() -> dict:
    url = "https://www.wikidata.org/w/rest.php/wikibase/v1/entities/items/Q107014860/sitelinks"
    data = get_url_curl(url)
    try:
        return json.loads(data or "{}")
    except json.JSONDecodeError:
        return {}


def Get_MdWiki_Category(lang: str) -> str:
    if lang in {"it"}:
        return ""
    cats = get_cats()
    return cats.get(f"{lang}wiki", {}).get("title", "Category:Translated from MDWiki")


def add_Translated_from_MDWiki(text: str, lang: str) -> str:
    if not text:
        return text
    if re.search(r":\s*Translated[ _]from[ _]MDWiki\s*\]\]", text, flags=re.IGNORECASE):
        return text
    cat = Get_MdWiki_Category(lang)
    if cat and cat not in text:
        text += f"\n[[{cat}]]\n"
    return text


__all__ = ["add_Translated_from_MDWiki", "Get_MdWiki_Category", "get_url_curl"]
