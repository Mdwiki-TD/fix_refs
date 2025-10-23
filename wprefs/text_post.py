"""Processing logic for the legacy ``text_post.php`` endpoint."""

from __future__ import annotations

from typing import Mapping

from .work import DoChangesToText1

FIELDS = ["lang", "title", "text", "revid", "sourcetitle"]
REQUIRED = {"lang", "title", "text"}


def process_request(data: Mapping[str, str]) -> str:
    values = {field: (data.get(field, "") or "").strip() for field in FIELDS}
    for field in REQUIRED:
        if not values[field]:
            return f"Missing required field: {field}"

    lang = values["lang"]
    title = values["title"]
    text = values["text"]
    revid = values["revid"]
    sourcetitle = values["sourcetitle"]

    new_text = DoChangesToText1(sourcetitle, title, text, lang, revid)
    if new_text.strip() == text.strip():
        return "no changes"
    return new_text


__all__ = ["process_request"]
