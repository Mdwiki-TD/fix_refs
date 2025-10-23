"""CSRF helpers translated from the PHP implementation."""

from __future__ import annotations

import secrets
from typing import MutableSequence, MutableMapping, Optional


_tokens: set[str] = set()


def generate_csrf_token(session: Optional[MutableMapping[str, MutableSequence[str]]] = None) -> str:
    token = secrets.token_hex(32)
    if session is not None:
        session.setdefault("csrf_tokens", []).append(token)
    else:
        _tokens.add(token)
    return token


def verify_csrf_token(
    submitted_token: Optional[str] = None,
    session: Optional[MutableMapping[str, MutableSequence[str]]] = None,
) -> bool:
    if session is not None:
        store = session.setdefault("csrf_tokens", [])
    else:
        store = list(_tokens)

    if not store:
        if session is not None:
            return True
        return True

    if not submitted_token:
        return False

    if submitted_token in store:
        if session is not None:
            store.remove(submitted_token)
        else:
            _tokens.discard(submitted_token)
        return True

    return False


__all__ = ["generate_csrf_token", "verify_csrf_token"]
