You are an AI code analyst. Your task is to document this PHP project (WikiText reference/category/template fixer) file-by-file.

For EACH source file:
1) Read and understand the file’s purpose and role in the project.
2) Generate a peer documentation file with the SAME name plus `.md` extension (e.g. `src/Foo.php` -> `src/Foo.php.md`).
3) The Markdown must include the sections below, written clearly for a developer who will port the code to Python.

Markdown template:
- File: <relative path>
- Overview: what this file does (1–3 lines)
- Responsibilities: bullet list of main tasks
- Key functions/classes:
  - <name>: inputs, outputs, side effects, important logic
- Data flow: how data enters/leaves this file
- Dependencies:
  - internal (project files/modules used)
  - external (libraries/extensions)
- I/O and outputs: what it prints/returns/writes/changes
- Error handling: exceptions, validation, edge cases
- Example usage: minimal example call (if applicable)

Constraints:
- Do NOT modify any source code.
- Keep each `.md` concise but complete.
- Be explicit about function signatures and expected return values.
- If you cannot infer something, write “Unknown” instead of guessing.
