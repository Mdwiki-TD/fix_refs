"""Whitespace cleanup around reference tags."""

from __future__ import annotations

import re
from typing import List, Tuple

from ..TestBot import echo_debug


def match_it(text: str, charters: str) -> str | None:
    pattern = re.compile(rf"(<\/ref>|\/>)\s*([{re.escape(charters)}]\s*)$", re.UNICODE)
    match = pattern.search(text)
    return match.group(2) if match else None


def get_parts(newtext: str, charters: str) -> List[Tuple[str, str]]:
    parts = newtext.split("\n\n")
    if len(parts) == 1:
        parts = newtext.split("\r\n\r\n")
    echo_debug(f"count(matches)={len(parts)}\n")
    new_parts: List[Tuple[str, str]] = []
    for part in parts:
        charter = match_it(part, charters)
        if charter:
            new_parts.append((part, charter))
    echo_debug(f"count(new_parts)={len(new_parts)}\n")
    return new_parts


def remove_spaces_between_last_word_and_beginning_of_ref(newtext: str, lang: str) -> str:
    dot = "\.,。।"
    if lang == "hy":
        dot = "\.,。।։:"
    parts = get_parts(newtext, dot)
    for part, charter in parts:
        echo_debug(f"charter={charter}\n")
        regline = re.compile(r"((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)", re.UNICODE)
        matches = regline.findall(part)
        echo_debug(f"count(last_ref)={len(matches)}\n")
        if matches:
            ref_text = matches[-1]
            end_part = f"{ref_text}{charter}"
            if part.rstrip().endswith(end_part):
                echo_debug("endswith\n")
                prefix = part[: -len(end_part)].rstrip()
                new_part = f"{prefix}{ref_text.strip()}{charter}"
                newtext = newtext.replace(part, new_part)
    return newtext


def remove_spaces_between_ref_and_punctuation(text: str, lang: str | None = None) -> str:
    dots = ".,。։۔:"
    cls = re.escape(dots)
    text = re.sub(rf"(<ref[^>]*\/>)\s*([{cls}])", r"\1\2", text, flags=re.UNICODE)
    text = re.sub(rf"</ref>\s*([{cls}])", r"</ref>\1", text, flags=re.UNICODE)
    return text


__all__ = [
    "remove_spaces_between_last_word_and_beginning_of_ref",
    "remove_spaces_between_ref_and_punctuation",
]
