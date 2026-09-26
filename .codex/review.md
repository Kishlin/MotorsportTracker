# Codex guidance

Codex is primarily a reviewer in this repository. The usual workflow is:
Claude Code implements and tests changes, Codex reviews them, then Claude Code
addresses the findings and commits. Apply the review workflow below when asked
to review. Explicit requests for other work define their own scope.

## Review workflow

- Leave files and Git state unchanged. Do not fix code, write tests, format,
  stage, commit, or revert changes during a review.
- Use the scope requested by the user. If none is specified, review all current
  uncommitted changes: staged, unstaged, and untracked files. Read relevant
  surrounding code, callers, schemas, and tests to understand their effects.
- Assume tests are passing and have already been run by Claude Code. Do not run
  tests, builds, linters, formatters, or other verification scripts unless the
  user explicitly requests them. Do not start containers, apply migrations,
  install dependencies, or invoke scraping or live API commands for a review.
- Passing tests are a premise, not proof that every edge case is covered. Read
  tests to understand coverage and identify missing cases that expose a concrete
  defect. Do not claim to have executed checks or verified their results.
- Focus on correctness, edge cases, error handling, data integrity, concurrency,
  compatibility, and consequential architecture violations. Trace a plausible
  failure through the code before reporting it.
- Report issues introduced or exposed by the reviewed changes. Avoid unrelated
  existing defects, speculative concerns, stylistic preferences, and broad
  refactoring suggestions. Respect the project's explicit conventions.

## Shared project guidance

Read the root [CLAUDE.md](../CLAUDE.md) for project structure, design principles,
and communication preferences. Read the following files when the reviewed scope
includes their area, including relevant code inspected outside the diff:

| Area | Instructions |
|---|---|
| `src/Golang/` | [Go core](../src/Golang/CLAUDE.md) |
| `etc/Migrations/` | [Migrations](../etc/Migrations/CLAUDE.md) |
| `apps/MotorsportTracker/Frontend/` | [Frontend](../apps/MotorsportTracker/Frontend/CLAUDE.md) |
| Go files under `src/Golang/` or `apps/Backend/` | [Go style](../.claude/rules/go-style.md) |
| Go test files under those directories | [Go tests](../.claude/rules/go-tests.md) |

Also read any more local `CLAUDE.md` or `AGENTS.md` files governing the reviewed
files. These documents remain the source of project conventions; do not maintain
duplicate copies here. Verify technical claims against the implementation when
they matter to a finding, since documentation can drift.

Apply shared guidance as review criteria. Instructions there to implement,
format, run checks, migrate, scrape, or commit do not authorize those actions
during a review; the review workflow above takes precedence. Claude-specific
hooks, permissions, slash commands, and automatic loading behavior do not carry
over to Codex. Skill files may be read as reference material without executing
their workflows. The restriction on new PHP development does not exclude PHP
changes from an explicitly requested review.

## Findings

- Lead with actionable findings, ordered by severity. Use `[P1]` for urgent
  defects, `[P2]` for normal defects, and `[P3]` for minor actionable issues.
  Reserve `[P0]` for unconditional, critical blockers.
- Give each finding a concise title, a precise file and line location in the
  changed code, the triggering input or circumstances, and the concrete impact.
  Explain why the current code fails; identify the relevant project rule when
  the finding depends on one. Include a suggested fix direction when useful.
- Separate unresolved questions from confirmed findings. Do not invent issues
  to meet a quota or treat a missing test alone as proof of a bug.
- If no actionable issues are found, say so plainly. When mentioning test
  status, say tests were assumed passing and were not run during this review.
- Be direct and challenge incorrect assumptions. Keep feedback concise enough
  for Claude Code to act on without needing the review conversation.
