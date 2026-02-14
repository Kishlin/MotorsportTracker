#!/bin/bash
set -euo pipefail

cd "$(dirname "$0")/.."

echo "Checking Architecture Compliance..."
echo ""

ERRORS=0

# Check 1: domain/ packages should NOT import infrastructure/ packages
echo "[1/3] Checking domain packages for infrastructure imports..."
INFRA_IN_DOMAIN=$(grep -rn '"github.com/kishlin/MotorsportTracker/.*infrastructure' --include='*.go' src/Golang/motorsporttracker/scrapping/*/domain/ src/Golang/motorsportstats/gateway/domain/ src/Golang/shared/*/domain/ 2>/dev/null | grep -v '_test.go' || true)

if [ -n "$INFRA_IN_DOMAIN" ]; then
  echo "FAIL: Domain packages import infrastructure (violates hexagonal architecture)"
  echo "$INFRA_IN_DOMAIN"
  ERRORS=$((ERRORS + 1))
else
  echo "PASS: Domain packages are infrastructure-independent"
fi

echo ""

# Check 2: domain/ packages should NOT import database, messaging, or client packages
echo "[2/3] Checking domain packages for framework imports..."
FRAMEWORK_IN_DOMAIN=$(grep -rn '"github.com/kishlin/MotorsportTracker/src/Golang/shared/\(database\|messaging\|client\|cache/infrastructure\)' --include='*.go' src/Golang/motorsporttracker/scrapping/*/domain/ src/Golang/motorsportstats/gateway/domain/ 2>/dev/null | grep -v '_test.go' || true)

if [ -n "$FRAMEWORK_IN_DOMAIN" ]; then
  echo "FAIL: Domain packages import framework packages (database, messaging, client)"
  echo "$FRAMEWORK_IN_DOMAIN"
  ERRORS=$((ERRORS + 1))
else
  echo "PASS: Domain packages don't depend on framework packages"
fi

echo ""

# Check 3: domain/ packages should NOT import pgx, aws-sdk, or other external infra libraries
echo "[3/3] Checking domain packages for external infrastructure libraries..."
EXTERNAL_IN_DOMAIN=$(grep -rn '"github.com/jackc/pgx\|"github.com/aws/\|"github.com/golang-migrate' --include='*.go' src/Golang/motorsporttracker/scrapping/*/domain/ src/Golang/motorsportstats/gateway/domain/ src/Golang/shared/*/domain/ 2>/dev/null | grep -v '_test.go' || true)

if [ -n "$EXTERNAL_IN_DOMAIN" ]; then
  echo "FAIL: Domain packages import external infrastructure libraries"
  echo "$EXTERNAL_IN_DOMAIN"
  ERRORS=$((ERRORS + 1))
else
  echo "PASS: Domain packages are free of external infrastructure dependencies"
fi

echo ""
echo "======================================="

if [ $ERRORS -eq 0 ]; then
  echo "Architecture compliance: PASSED"
  echo "  Domain layers are clean and portable!"
  exit 0
else
  echo "Architecture compliance: FAILED ($ERRORS violation(s))"
  echo "  Fix violations to maintain hexagonal architecture"
  exit 1
fi
