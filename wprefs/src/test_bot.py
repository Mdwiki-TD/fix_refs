"""Translation of ``src/test_bot.php``.

The original PHP helper prints debugging output when the web request
contains a ``test`` parameter or when the ``DEBUG`` constant is defined.
The Python port emulates the same behaviour with module level flags so
that unit tests (and future Flask/CLI front-ends) can control the
verbosity without relying on PHP super-globals.
"""

from __future__ import annotations

import os
from typing import Any

TEST_MODE_ENV = "WPREFS_TEST"
DEBUG_ENV = "WPREFS_DEBUG"

_test_enabled = False
_debug_enabled = False


def _as_bool(value: Any) -> bool:
    """Interpret ``value`` using the same loose truthiness as PHP.

    PHP treats most non-empty values—including ``"0"``—as ``True`` when
    evaluated in a boolean context.  The helper mirrors that behaviour so
    the port stays compatible with existing scripts.
    """

    if isinstance(value, bool):
        return value
    if value is None:
        return False
    if isinstance(value, (int, float)):
        return value != 0
    text = str(value).strip()
    return text != "" and text.lower() not in {"false", "off"}


def enable_test_mode(flag: bool = True) -> None:
    """Enable or disable ``echo_test`` output programmatically."""

    global _test_enabled
    _test_enabled = bool(flag)


def enable_debug(flag: bool = True) -> None:
    """Enable or disable ``echo_debug`` output programmatically."""

    global _debug_enabled
    _debug_enabled = bool(flag)


def _is_test_enabled() -> bool:
    if _test_enabled:
        return True
    env_value = os.environ.get(TEST_MODE_ENV)
    return _as_bool(env_value)


def _is_debug_enabled() -> bool:
    if _debug_enabled:
        return True
    env_value = os.environ.get(DEBUG_ENV)
    return _as_bool(env_value)


def echo_test(message: str) -> None:
    """Print ``message`` if the testing flag is active.

    The call mirrors the PHP routine by appending a newline and writing to
    ``stdout``.  The return value is ``None`` to make the function easy to
    stub in tests.
    """

    if _is_test_enabled():
        print(message)


def echo_debug(message: str) -> None:
    """Print debugging messages when debug mode is active."""

    if _is_debug_enabled():
        print(message)


__all__ = [
    "echo_test",
    "echo_debug",
    "enable_test_mode",
    "enable_debug",
]
