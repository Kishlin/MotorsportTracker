#!/bin/bash
set -euo pipefail

cd "$(dirname "$0")/.."

usage() {
  echo "Usage: $0 [scope] [--verbose] [--run <pattern>] [--pristine]" >&2
  exit 1
}

SCOPE=""
VERBOSE=false
RUN_PATTERN=""
PRISTINE=false

while [ $# -gt 0 ]; do
  case "$1" in
    --verbose)
      VERBOSE=true
      ;;
    --run)
      [ $# -ge 2 ] || usage
      RUN_PATTERN="$2"
      shift
      ;;
    --pristine)
      PRISTINE=true
      ;;
    -*)
      echo "Unknown flag: $1" >&2
      usage
      ;;
    *)
      [ -z "$SCOPE" ] || usage
      SCOPE="$1"
      ;;
  esac
  shift
done

case "${SCOPE:-all}" in
  all)
    # Every module in go.work, so a new app is tested without being listed here.
    MODULES=$(docker compose exec -T --workdir /app golang go list -m -f '{{.Dir}}/...')
    mapfile -t TARGETS <<< "$MODULES"
    ;;
  scrapping)
    TARGETS=(./src/Golang/motorsporttracker/scrapping/...)
    ;;
  gateway)
    TARGETS=(./src/Golang/motorsportstats/...)
    ;;
  shared)
    TARGETS=(./src/Golang/shared/...)
    ;;
  *)
    TARGETS=("$SCOPE")
    ;;
esac

FLAGS=()

if [ "$VERBOSE" = true ]; then
  FLAGS+=(-v)
fi

if [ -n "$RUN_PATTERN" ]; then
  FLAGS+=(-run "$RUN_PATTERN")
fi

if [ "$PRISTINE" = true ]; then
  echo "Clearing test cache..."
  docker compose exec golang go clean -testcache
  echo ""
fi

./scripts/fixture-prefix-check.sh

echo "Running tests: ${TARGETS[*]}"
if [ ${#FLAGS[@]} -gt 0 ]; then
  echo "Flags: ${FLAGS[*]}"
fi
echo ""

for TARGET in "${TARGETS[@]}"; do
  docker compose exec --workdir /app golang go test "${FLAGS[@]}" "$TARGET"
done

echo ""
echo "Tests completed."
