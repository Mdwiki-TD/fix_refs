#
import re
from pathlib import Path
from tqdm import tqdm


def match_it(text, charters):
    m = re.search(f'(</ref>|\/>)\s*([{charters}]\s*)$', text, flags=re.UNICODE)
    if m:
        return m.group(2)
    return None


def get_parts(newtext, charters):
    pattern = r'(.+?)(\n\n|\Z)'
    parts = re.findall(pattern, newtext, re.DOTALL)
    # ---
    # get only parts endswith one of charters
    # parts = [(p[0], match_it(p[0])) for p in parts if re.search(f'(</ref>|\/>)\s*[{charters}]\s*$', p[0], flags=re.UNICODE)]
    new_parts = []
    # ---
    print(f"{len(parts)=}")
    # ---
    for p in parts:
        chart = match_it(p[0], charters)
        if chart:
            new_parts.append((p[0], chart))
    # ---
    print(f"{len(new_parts)=}")
    # ---
    return new_parts


def remove_spaces_between_last_word_and_beginning_of_ref(newtext: str, lang: str) -> str:

    # --- 1) تحديد علامات الترقيم
    dot = r"\.,。।"

    if lang == "hy":
        dot = r"\.,。।։"

    parts = get_parts(newtext, dot)
    # ---
    for part, charter in parts:
        # ---
        # print([part])
        print(f"{charter=}")
        # ---
        regline = r"((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)"
        # ---
        # find last ref group
        last_ref = re.findall(regline, part, re.DOTALL)
        # ---
        print(f"{len(last_ref)=}")
        # ---
        if last_ref:
            # ---
            ref_text = last_ref[-1]
            # ---
            end_part = f"{ref_text}{charter}"
            # ---
            if part.endswith(end_part):
                # ---
                print("endswith ")
                # ---s
                new_part = part.split(end_part)[0].strip() + f"{ref_text.strip()}{charter}"
                # ---
                newtext = newtext.replace(part, new_part)

    return newtext


def assert_equal_compare(expected: str, input_text: str, result: str):
    if result == expected:
        print("result === expected")
    elif result == input_text:
        print("result === input")
    else:
        print("result !== expected")


# --- الملفات
base_path = Path(__file__).parent.parent.parent / "tests/remove_space_texts"

for i in tqdm([1, 2, 3, 4]):
    base_path_sub = base_path / str(i)
    expected=(base_path_sub / "expected.txt").read_text(encoding="utf-8")
    input_text=(base_path_sub / "input.txt").read_text(encoding="utf-8")
    output_file=base_path_sub / "output.txt"

    # --- تطبيق الدالة
    result=remove_spaces_between_last_word_and_beginning_of_ref(input_text, "hy")

    assert_equal_compare(expected, input_text, result)

    # --- حفظ النتيجة
    output_file.write_text(result, encoding="utf-8")
    print(f"\n saved to: {output_file}")
