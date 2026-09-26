#!/bin/bash
set -euo pipefail

cd "$(dirname "$0")/.."

# CODE_STYLE.md: compare booleans explicitly (`ok == false`), never with the `!` operator.
# The rewrite rule matches the unary `!` in the syntax tree, so `!=` and a `!` inside a
# string or comment never trip it. `gofmt -l` lists the files the rewrite would change.

[ $# -gt 0 ] || set -- src/Golang apps/Backend

VIOLATIONS=$(gofmt -l -r '!a -> a == false' "$@")

if [ -n "$VIOLATIONS" ]; then
  printf 'FAIL: %s\n' $VIOLATIONS
  echo "Negation check: FAILED — write \`x == false\` instead of \`!x\` (fix: gofmt -w -r '!a -> a == false' <file>)"
  exit 1
fi
