import sys
sys.path.insert(0, '.')
from pathlib import Path
from src.lang_bots.es_helpers import get_refs, add_line_to_temp, make_line
import re

tests_dir = Path('pytests/es_bots/texts')
with open(tests_dir / 'es_refs_input.txt', 'r', encoding='utf-8') as f:
    text_input = f.read()

# Check what refs match in the input
print("=== Refs matching in input ===")
for match in re.finditer(r'<ref([^>]*?)>(.*?)</ref>', text_input, re.IGNORECASE | re.DOTALL):
    if 'Doed1998' in match.group(0):
        print(f"Doed1998 match: {repr(match.group(0))}")
        print(f"  attrs: {repr(match.group(1))}")
        print(f"  content: {repr(match.group(2))}")

# Step 1: get_refs
refs_result = get_refs(text_input)
print("\n=== After get_refs ===")
print("Doed1998 in new_text:", '<ref name=Doed1998/>' in refs_result['new_text'])
print("Doed1998/ in new_text:", '<ref name=Doed1998/ />' in refs_result['new_text'])

# Find Doed1998 refs
for match in re.finditer(r'<ref name=Doed1998[^>]*>', refs_result['new_text']):
    print(f"Doed1998 ref in new_text: {repr(match.group(0))}")

# Step 2: make_line
line = make_line(refs_result['refs'])
print("\n=== make_line result ===")
print("Doed1998 in line:", 'Doed1998' in line)
if 'Doed1998' in line:
    for match in re.finditer(r'<ref name=Doed1998[^>]*>', line):
        print(f"Doed1998 ref in line: {repr(match.group(0))}")

# Step 3: add_line_to_temp
final_result = add_line_to_temp(line, refs_result['new_text'])
print("\n=== After add_line_to_temp ===")
for match in re.finditer(r'<ref name=Doed1998[^>]*>', final_result):
    print(f"Doed1998 ref in final: {repr(match.group(0))}")
