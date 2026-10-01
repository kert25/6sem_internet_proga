# Lab Work Structure Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Migrate the project to the `lab-works` state model and make the
universal runner support methodical report numbers and custom lab directories.

**Architecture:** `_lab_state.json` becomes the machine-readable operational
state, while the existing PDF guides remain the authoritative assignment source.
`lab_runner.py` reads optional metadata (`directory`, `report_number`) with
compatible defaults, so existing version-1 state files continue to work. The
skill documentation and eval scenarios specify the same contracts.

**Tech Stack:** UTF-8 JSON, Python 3 standard library (`pathlib`, `unittest`),
existing `make_docx.py` and `inspect_docx.py` utilities.

**Spec:** `docs/superpowers/specs/2026-10-01-lab-work-structure-design.md`

## Global Constraints

- Methodical guides are the primary source for official number, topic,
  assignment, and requirements; a folder name is only an organizational path.
- Preserve all source code, guides, generated reports, `ЛР_пример.docx`, and
  the Tula-specific `_tools/make_docx.py`.
- Keep existing tool copies in `_tools/`; this migration must not delete them.
- Use UTF-8 JSON and retain ASCII-safe CLI usage: `--lab` selects the state
  record, while Cyrillic paths and topics come from JSON.
- `report_number` and `directory` are optional and must default to the old
  `ЛР<lab number>` / numeric-title behavior for state version 1.
- Do not create git commits unless the user explicitly requests one.

## Review Focus

- State record omits `report_number`: report generation must use the selected
  lab key exactly as legacy versions did.
- State record omits `directory`: all default content/report paths must remain
  under `ЛР<lab number>`.
- `methodical_guide` is supplied but missing: report building must stop before
  creating or overwriting a report.
- `methodical_guide` is absent: older records must remain valid and buildable.
- A report number such as `9` differs from the directory key `1`: the file
  remains `ЛР1/Отчет_ЛР1.docx`, while the title page receives `9`.

---

### Task 1: Add runner unit coverage for extended state metadata

**Files:**
- Create: `~/.agents/skills/lab-works/scripts/tests/test_lab_runner.py`
- Modify: `~/.agents/skills/lab-works/scripts/lab_runner.py` (only after the
  failing tests are established in Task 2)

**Interfaces:**
- Consumes: `paths_for(root: Path, lab_number: int, lab: dict)` and
  `build_report(root: Path, lab_number: int, lab: dict)` from `lab_runner.py`.
- Produces: executable regression coverage for legacy path defaults, custom
  directories, official report numbers, and methodical-guide validation.

- [ ] **Step 1: Write a failing test for custom and default directories**

Use a temporary root and assert that `paths_for(root, 1, {})` resolves content
and report to `ЛР1/content.json` and `ЛР1/Отчет_ЛР1.docx`. Add a record with
`{"directory": "practice/module-a"}` and assert both paths resolve inside
that directory.

- [ ] **Step 2: Run the directory-path test to verify it fails**

Run: `py -m unittest scripts.tests.test_lab_runner.RunnerPathsTest.test_paths_use_directory_or_legacy_default`

Expected: FAIL because `paths_for()` currently always constructs `ЛР<lab>`.

- [ ] **Step 3: Write a failing test for official report numbers**

Patch `lab_runner.build` and `lab_runner.load_context`; call `build_report()`
with `lab_number=1`, `directory="ЛР1"`, `report_number=9`, an existing
`content.json`, and a declared guide. Assert `build()` receives `9` as its
second positional argument and writes to `ЛР1/Отчет_ЛР1.docx`.

- [ ] **Step 4: Write a failing test for methodical-guide validation and its legacy absence**

Call `build_report()` with a non-existent `methodical_guide` and assert it
raises `SystemExit` containing `methodical_guide`. Then call it with no
`methodical_guide` and mocked report dependencies; assert the legacy record
continues to call `build()`.

- [ ] **Step 5: Run all new runner tests to verify the intended failures**

Run: `py -m unittest scripts.tests.test_lab_runner -v`

Expected: the custom-directory, report-number, and missing-guide tests fail;
the legacy expectation documents the current compatible behavior.

### Task 2: Implement backwards-compatible runner metadata

**Files:**
- Modify: `~/.agents/skills/lab-works/scripts/lab_runner.py:54-79`
- Test: `~/.agents/skills/lab-works/scripts/tests/test_lab_runner.py`

**Interfaces:**
- Consumes: a selected lab `dict` with optional `directory`, `report_number`,
  and `methodical_guide` fields.
- Produces: `paths_for(root, lab_number, lab)` resolving the configured
  directory; `build_report(root, lab_number, lab)` passing the official report
  number to `make_docx.build()`.

- [ ] **Step 1: Implement `lab_directory(root: Path, lab_number: int, lab: dict) -> Path`**

Place it beside `resolve()`. It must resolve `lab["directory"]` relative to
`root` when the field is a non-empty string; otherwise return
`root / ("ЛР%d" % lab_number)`.

- [ ] **Step 2: Update `paths_for(root: Path, lab_number: int, lab: dict)`**

Use `lab_directory()` for default `content` and `report` paths. Preserve
explicit `content`, `report`, and `context` overrides exactly as they work
now.

- [ ] **Step 3: Implement `report_number_for(lab_number: int, lab: dict) -> int`**

Return `lab["report_number"]` when it is an integer; otherwise return
`lab_number`. Reject booleans and invalid types through `fail()` with a
message naming `report_number`, rather than silently accepting them.

- [ ] **Step 4: Validate a declared methodical guide in `build_report()`**

When `methodical_guide` exists and is a non-empty string, resolve it relative
to `root`; require `is_file()` before reading content or creating the report
parent. Use `fail("не найдена methodical_guide: ...")` on failure. Do not make
the field mandatory for legacy state files.

- [ ] **Step 5: Call `build()` with `report_number_for()`**

Keep the existing report filename based on `paths_for()`. Only the title-page
number passed as the second positional `build()` argument changes.

- [ ] **Step 6: Run unit tests**

Run: `py -m unittest scripts.tests.test_lab_runner -v`

Expected: PASS, including every Review Focus case.

- [ ] **Step 7: Run legacy smoke test**

Run: `py ~/.agents/skills/lab-works/scripts/lab_runner.py --help`

Expected: exit code 0 and unchanged `--root`, `--lab`, and `--action` CLI
arguments.

### Task 3: Document universal state extensions and evaluation cases

**Files:**
- Modify: `~/.agents/skills/lab-works/SKILL.md` (sections `_lab_state.json`,
  «Структура проекта», «ASCII-safe запуск»)
- Modify: `~/.agents/skills/lab-works/evals/evals.json`

**Interfaces:**
- Consumes: the `directory`, `report_number`, `methodical_guide`,
  `shared_resources`, `project_notes`, and `notes` contract implemented by
  `lab_runner.py`.
- Produces: user-facing rules and eval expectations that agree with the
  runner’s behavior.

- [ ] **Step 1: Update the `_lab_state.json` example and field documentation**

Set its `version` to `2`. Explain that `methodical_guide` is the authoritative
reference, `directory` is an optional working path, `report_number` is the
optional official number for a title page, and `project_notes`/`notes` store
only confirmed decisions not duplicated from a guide. Document
`shared_resources` as a list of references that are not copied.

- [ ] **Step 2: State the precedence rule in the workflow**

Add a concise rule that agents read the named guide before inferring anything
from a folder. If guide information conflicts with `report_number` or `theme`,
the agent must surface the conflict and request clarification instead of
silently choosing one.

- [ ] **Step 3: Add `_libs/` to the optional project structure**

Describe it as a root-level store for deliberate local shared dependencies,
such as offline JavaScript libraries. State that it is optional and should not
cause unnecessary copies into each lab directory.

- [ ] **Step 4: Update the ASCII-safe runner example**

Clarify that `--lab N` selects the `labs.N` state record; it does not
necessarily equal the methodical/title-page number, which comes from
`report_number` when supplied.

- [ ] **Step 5: Add four eval records to `evals/evals.json`**

Cover: (a) `ЛР1` with `report_number: 9`, requiring title page 9 and report
file `Отчет_ЛР1.docx`; (b) a custom `directory`; (c) two labs referencing a
shared local dependency; (d) absent/conflicting guide metadata requiring a
clarifying request instead of a guess.

- [ ] **Step 6: Validate JSON and review documentation references**

Run: `py -m json.tool ~/.agents/skills/lab-works/evals/evals.json > NUL`

Expected: exit code 0. Manually verify all field names match Task 2 exactly.

### Task 4: Create the project state and remove superseded plans

**Files:**
- Create: `Интернет_программирование/_lab_state.json`
- Delete: `Интернет_программирование/ПЛАН_ЛАБОРАТОРНЫХ.md`
- Delete: `Интернет_программирование/ПЛАН_ДОРАБОТКИ_СКИЛЛА.md`
- Preserve: `Интернет_программирование/_context.json`,
  `Интернет_программирование/ЛР_пример.docx`, all `ЛРN/` contents, and all
  `_tools/` contents.

**Interfaces:**
- Consumes: state schema documented in Task 3 and the existing PDFs / ready
  artifacts in the project.
- Produces: a version-2 project state ready for resuming one laboratory at a
  time, without duplicate plan files.

- [ ] **Step 1: Create `_lab_state.json` with project-level confirmed decisions**

Use `version: 2`, `commit_on_completion: false`, and `project_notes` for the
methodical-guide precedence, group-list variant `9`, shared XML «Видеотека»
for ЛР4–ЛР6, PHP built-in server for ЛР7, local jQuery dependencies for ЛР8,
commented code, and the confirmed cyclic individual-task mapping for ЛР8.

- [ ] **Step 2: Add the completed-work record for ЛР1**

Use key `"1"`, `directory: "ЛР1"`, the actual existing guide path,
`report_number: 9`, theme «Основы JavaScript», `variant: null`, status
`ready_for_review`, and the existing DOCX, PDF, `text.docx`, plus the
`screenshots/` directory as artifacts. Set `next_step` to user review and
feedback; do not mark it completed.

- [ ] **Step 3: Add not-started records for ЛР2–ЛР8**

Each record must use the exact existing PDF filename, `directory: "ЛРN"`,
`report_number` 10–16, the theme from its guide, `variant: "9"`,
`status: "not_started"`, an empty artifact list, and a next step that begins
by reading the guide. Add per-lab notes only for confirmed non-methodical
choices: XML «Видеотека» for ЛР4–ЛР6; PHP server for ЛР7; local jQuery paths
and the cyclic task mapping for ЛР8.

- [ ] **Step 4: Validate state and referenced guides**

Run: `py -m json.tool _lab_state.json > NUL`

Run: `py -c "import json, pathlib; s=json.load(open('_lab_state.json', encoding='utf-8')); missing=[x['methodical_guide'] for x in s['labs'].values() if not pathlib.Path(x['methodical_guide']).is_file()]; print('OK' if not missing else missing)"`

Expected: valid JSON and `OK`.

- [ ] **Step 5: Run the migrated runner verification for ЛР1**

Run: `py ~/.agents/skills/lab-works/scripts/lab_runner.py --root . --lab 1 --action verify-report`

Expected: `OK` for the three existing document artifacts. If the global runner
is not yet available locally, run this after Task 2 and use the same command.

- [ ] **Step 6: Delete only the two superseded plan files**

Delete `ПЛАН_ЛАБОРАТОРНЫХ.md` and `ПЛАН_ДОРАБОТКИ_СКИЛЛА.md` only after Steps
1–5 have passed. Keep the approved design and this implementation plan in
`docs/superpowers/`.

- [ ] **Step 7: Perform a final structure audit**

Run: `py -c "import pathlib; required=['_context.json','_lab_state.json','ЛР_пример.docx']; missing=[p for p in required if not pathlib.Path(p).is_file()]; print('OK' if not missing else missing)"`

Expected: `OK`; verify `ЛР1/Отчет_ЛР1.docx`, `ЛР1/Отчет_ЛР1.pdf`, and
`ЛР1/text.docx` still exist; verify both deleted plan paths do not exist.

- [ ] **Step 8: Request a commit decision**

Report the validated migration and ask the user whether to create a commit.
Do not run `git commit` unless the user gives direct permission.

## Plan self-review

- **Spec coverage:** Tasks 1–2 implement the runtime schema, Task 3 documents
  and evaluates it, Task 4 applies the migration and deletes duplicate plans.
  All preservation, compatibility, guide-precedence, and validation
  requirements are covered.
- **Step scan:** Each step has one concrete edit or command, with exact field
  names and expected effects.
- **Type consistency:** Task 2 defines `lab_directory()` and
  `report_number_for()`; Task 1 tests `paths_for()` and `build_report()` that
  consume them; Task 3 documents the same JSON field spellings.
- **Review Focus:** Tests in Task 1 cover each listed legacy, custom-path,
  official-number, and guide-presence case.
- **Proportion:** The plan gives only contracts and test assertions; it does
  not prescribe implementation bodies beyond decisions fixed by the spec.
