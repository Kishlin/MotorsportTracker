#!/bin/bash
set -euo pipefail
shopt -s inherit_errexit

cd "$(dirname "$0")/.."

# The Go applications are every main package in go.work. Each is addressed by its
# directory name (ApiCanary) and builds to build/<kebab-case name> inside that
# directory, capital runs read as acronyms (DBMigrate -> build/db-migrate).
# Runs inside the golang container.

usage() {
  echo "Usage: $0 build [App]" >&2
  echo "       $0 run <App> [args...]" >&2
  exit 1
}

main_packages() {
  local modules
  modules=$(go list -m -f '{{.Dir}}/...')
  go list -f '{{if eq .Name "main"}}{{.Dir}}{{end}}' $modules | sed '/^$/d'
}

binary_name() {
  basename "$1" | sed -E 's/([a-z0-9])([A-Z])/\1-\2/g; s/([A-Z])([A-Z][a-z])/\1-\2/g' | tr '[:upper:]' '[:lower:]'
}

app_dir() {
  local dirs dir
  dirs=$(main_packages)
  for dir in $dirs; do
    if [ "$(basename "$dir")" = "$1" ]; then
      echo "$dir"
      return
    fi
  done
  echo "Unknown app: $1. Apps: $(echo $dirs | xargs -n1 basename | tr '\n' ' ')" >&2
  exit 1
}

# Progress goes to stderr so `run` leaves the app's own stdout untouched.
build() {
  local name
  name=$(binary_name "$1")
  echo "Building $name" >&2
  (cd "$1" && go build -o "build/$name" .)
}

[ $# -ge 1 ] || usage

case "$1" in
  build)
    [ $# -le 2 ] || usage
    if [ $# -eq 2 ]; then
      DIRS=$(app_dir "$2")
    else
      DIRS=$(main_packages)
    fi
    for DIR in $DIRS; do
      build "$DIR"
    done
    ;;
  run)
    [ $# -ge 2 ] || usage
    DIR=$(app_dir "$2")
    shift 2
    build "$DIR"
    BINARY="$DIR/build/$(binary_name "$DIR")"
    exec "$BINARY" "$@"
    ;;
  *)
    usage
    ;;
esac
