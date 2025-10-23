from __future__ import annotations

import time

from src.helps_bots.remove_space import remove_spaces_between_ref_and_punctuation


class remove_space2PerformanceTest:
    def testStressWithOneMillionRefs(self) -> None:
        dots = [".", "։", "。", "।", ":"]
        parts = []
        for i in range(1_000_000):
            dot = dots[i % len(dots)]
            if i % 2 == 0:
                parts.append(f"<ref name=\"T{i}\" /> {dot}")
            else:
                parts.append(f"</ref> {dot}")

        input_text = " ".join(parts)
        start = time.perf_counter()
        output = remove_spaces_between_ref_and_punctuation(input_text, "multi")
        duration = time.perf_counter() - start

        assert duration < 20.0, f"Function too slow on 1M refs ({duration:.2f}s)"
        for dot in dots:
            assert f" /> {dot}" not in output
            assert f"</ref> {dot}" not in output
