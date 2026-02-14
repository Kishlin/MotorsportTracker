#!/bin/bash
set -euo pipefail

cd "$(dirname "$0")/.."

SCOPE="${1:-all}"
VERBOSE="${2:-false}"
RUN_PATTERN="${3:-}"
PRISTINE="${4:-false}"

case "$SCOPE" in
  all)
    TARGETS="./src/Golang/... ./apps/Backend/DBMigrate/... ./apps/Backend/CommandsProcessor/... ./apps/Backend/CommandsPublisher/..."
    ;;
  scrapping)
    TARGETS="./src/Golang/motorsporttracker/scrapping/..."
    ;;
  gateway)
    TARGETS="./src/Golang/motorsportstats/..."
    ;;
  shared)
    TARGETS="./src/Golang/shared/..."
    ;;
  *)
    TARGETS="$SCOPE"
    ;;
esac

FLAGS=""

if [ "$VERBOSE" = "true" ]; then
  FLAGS="$FLAGS -v"
fi

if [ -n "$RUN_PATTERN" ]; then
  FLAGS="$FLAGS -run $RUN_PATTERN"
fi

if [ "$PRISTINE" = "true" ]; then
  echo "Clearing test cache..."
  docker compose exec golang go clean -testcache
  echo ""
fi

echo "Running tests: $TARGETS"
if [ -n "$FLAGS" ]; then
  echo "Flags:$FLAGS"
fi
echo ""

for TARGET in $TARGETS; do
  docker compose exec golang bash -c "cd /app && go test $FLAGS $TARGET"
done

echo ""
echo "Tests completed."
