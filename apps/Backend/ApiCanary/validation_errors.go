package main

import (
	"fmt"
	"regexp"
	"strings"
)

// maxReportedErrors bounds how many distinct validation errors a single check prints.
const maxReportedErrors = 8

// arrayIndex matches the numeric path segments the validator emits, so /details/0 and /details/17 fold
// together as one finding about /details.
var arrayIndex = regexp.MustCompile(`/\d+`)

// collapseValidationErrors folds the connector's validation output into one line per distinct problem.
//
// The validator reports every offending object separately, so a field missing from every row of a
// classification yields one error per row — thirty-five of them for an endurance race — which buries
// the single fact worth reading. Each group keeps its first error verbatim, so the offending value is
// still there to look at.
func collapseValidationErrors(message string) []string {
	lines := strings.Split(strings.TrimRight(message, "\n"), "\n")

	var order []string
	exemplars := map[string]string{}
	counts := map[string]int{}

	for _, line := range lines {
		line = strings.TrimSpace(line)
		if line == "" {
			continue
		}

		key := validationErrorKey(line)

		if _, seen := exemplars[key]; seen == false {
			order = append(order, key)
			exemplars[key] = line
		}

		counts[key]++
	}

	collapsed := make([]string, 0, len(order))

	for i, key := range order {
		if i == maxReportedErrors {
			collapsed = append(collapsed, fmt.Sprintf("... and %s", plural(len(order)-i, "further distinct error")))
			break
		}

		line := exemplars[key]
		if counts[key] > 1 {
			line += fmt.Sprintf("  (%s)", plural(counts[key], "occurrence"))
		}

		collapsed = append(collapsed, line)
	}

	return collapsed
}

// validationErrorKey fingerprints one validation error as its path with array indices removed, plus the
// human-readable tail. The validator formats an error as "<path>: <offending value> <message>" and
// truncates the value with an ellipsis, so the tail is what follows it; without one the value was short
// enough to keep and the whole line is used.
func validationErrorKey(line string) string {
	path, remainder, found := strings.Cut(line, ": ")
	if found == false {
		return arrayIndex.ReplaceAllString(line, "/*")
	}

	tail := remainder
	if _, afterEllipsis, truncated := strings.Cut(remainder, "... "); truncated {
		tail = afterEllipsis
	}

	return arrayIndex.ReplaceAllString(path, "/*") + "|" + tail
}
