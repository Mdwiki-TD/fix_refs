"""Move punctuation around reference tags, translated from PHP."""

from __future__ import annotations

import re


def move_dots_before_refs(text: str, lang: str) -> str:
    punctuation = r"\.,،"
    pattern = re.compile(rf"((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)([{punctuation}]+)", re.UNICODE)

    def repl(match: re.Match[str]) -> str:
        refs = match.group(1).strip()
        punct = match.group(2)
        if punct.count(".") > 1:
            punct = "."
        return f"{punct} {refs}"

    return pattern.sub(repl, text)


def move_dots_after_refs(newtext: str, lang: str) -> str:
    dot = r"\.,。।"
    if lang == "hy":
        dot = r"\.,。։।:"
    regline = r"((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)"
    pattern = re.compile(rf"([{dot}]+)\s*{regline}", re.UNICODE)
    return pattern.sub(lambda m: f"{m.group(2)}{m.group(1)}", newtext)


__all__ = ["move_dots_after_refs", "move_dots_before_refs"]
