#!/bin/bash
set -euo pipefail

cd "$(dirname "$0")/.."

# Integration suites share the core-test and client-cache test databases and run concurrently. Each suite
# seeds rows under a UUID prefix and tears down with `uuid LIKE '<prefix>-%'`, so two
# suites on the same prefix delete each other's fixtures and see each other's rows.
# A prefix is the first three UUID groups (8-4-4 hex); every UUID literal in a suite
# must start with the suite's one prefix, and no two suites may share it.

UUID_PREFIX='[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}'

ERRORS=0
declare -A OWNER

while IFS= read -r file; do
  prefixes=$(grep -oE "$UUID_PREFIX" "$file" | sort -u || true)
  [ -n "$prefixes" ] || continue

  if [ "$(printf '%s\n' "$prefixes" | wc -l)" -gt 1 ]; then
    echo "FAIL: $file uses more than one UUID prefix: $(printf '%s ' $prefixes)"
    ERRORS=$((ERRORS + 1))
    continue
  fi

  if [ -n "${OWNER[$prefixes]:-}" ]; then
    echo "FAIL: UUID prefix $prefixes is used by both ${OWNER[$prefixes]} and $file"
    ERRORS=$((ERRORS + 1))
    continue
  fi
  OWNER[$prefixes]=$file
done < <(grep -rlE 'POSTGRES_(CORE|CLIENT_CACHE)_URL' --include='*_test.go' src/Golang apps | sort)

if [ $ERRORS -ne 0 ]; then
  echo "Fixture prefix check: FAILED ($ERRORS violation(s)) — give each integration suite its own UUID prefix"
  exit 1
fi
