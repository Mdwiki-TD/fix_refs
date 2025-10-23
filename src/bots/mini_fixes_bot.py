"""Mini fixes helpers translated from ``src/bots/mini_fixes_bot.php``."""

from __future__ import annotations

import re
from typing import Dict


def fix_sections_titles(text: str, lang: str) -> str:
    replacements: Dict[str, Dict[str, str]] = {
        "hr": {
            "Reference": "Izvori",
            "References": "Izvori",
        },
        "sw": {
            "Reference": "Marejeo",
            "References": "Marejeo",
            "Marejeleo": "Marejeo",
        },
        "ru": {
            "Reference": "Примечания",
            "References": "Примечания",
            "Ссылки": "Примечания",
        },
    }

    mapping = replacements.get(lang)
    if not mapping:
        return text

    for key, value in mapping.items():
        pattern = re.compile(r"(=+)\s*{}\s*\1".format(re.escape(key)), re.IGNORECASE | re.UNICODE)
        text = pattern.sub(lambda match, value=value: f"{match.group(1)} {value} {match.group(1)}", text)
    return text


def remove_space_before_ref_tags(text: str, lang: str) -> str:
    pattern = re.compile(r"\s*([\.,。।])\s*<ref", re.IGNORECASE | re.UNICODE)
    return pattern.sub(r"\1<ref", text)


def refs_tags_spaces(text: str) -> str:
    text = re.sub(r"</ref>\s*<ref", "</ref><ref", text, flags=re.UNICODE)
    text = re.sub(r"/>\s*<ref", "/><ref", text, flags=re.UNICODE)
    text = text.replace("</ref> <ref", "</ref><ref")
    text = text.replace("> <ref", "><ref")
    return text


def fix_preffix(text: str, lang: str) -> str:
    text = re.sub(r"\[\[:en:", "[[", text, flags=re.UNICODE)
    pattern = re.compile(r"\[\[:{}:".format(re.escape(lang)), re.IGNORECASE | re.UNICODE)
    text = pattern.sub("[[", text)
    return text


def mini_fixes_after_fixing(text: str, lang: str) -> str:
    text = re.sub(r"^\s*\n", "\n", text, flags=re.MULTILINE | re.UNICODE)
    text = fix_preffix(text, lang)
    return text


def mini_fixes(text: str, lang: str) -> str:
    text = refs_tags_spaces(text)
    text = fix_sections_titles(text, lang)
    text = remove_space_before_ref_tags(text, lang)
    return text


__all__ = [
    "fix_sections_titles",
    "mini_fixes",
    "mini_fixes_after_fixing",
    "remove_space_before_ref_tags",
]
