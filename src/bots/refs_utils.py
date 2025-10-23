"""String helpers translated from ``src/bots/refs_utils.php``."""

from __future__ import annotations

def str_ends_with(text: str, suffix: str) -> bool:
    if suffix == "":
        return True
    return text.endswith(suffix)


def str_starts_with(text: str, prefix: str) -> bool:
    if prefix == "":
        return True
    return text.startswith(prefix)


def rm_str_from_start_and_end(text: str, find: str) -> str:
    if not find:
        return text
    text = text.strip()
    if str_starts_with(text, find) and str_ends_with(text, find):
        text = text[len(find) : len(text) - len(find)]
    return text.strip()


def remove_start_end_quotes(text: str) -> str:
    text = text.strip()
    text = rm_str_from_start_and_end(text, '"')
    text = rm_str_from_start_and_end(text, "'")
    quote = '"' if '"' not in text else "'"
    return f"{quote}{text}{quote}"


__all__ = [
    "str_ends_with",
    "str_starts_with",
    "rm_str_from_start_and_end",
    "remove_start_end_quotes",
]
