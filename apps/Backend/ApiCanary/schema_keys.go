package main

import (
	"encoding/json"
	"fmt"
	"os"
	"path/filepath"
	"sort"
	"strings"
)

// schemasDir is where the connector keeps the JSON Schemas it embeds at build time. They are read from
// disk here rather than exported from the connector package, so checking for drift costs the scraping
// code nothing.
const schemasDir = "src/Golang/motorsportstats/connector/infrastructure/schemas"

// loadSchema reads one of the connector's schemas by resource name (series, seasons, calendar,
// classification).
func loadSchema(projectDir string, resource string) ([]byte, error) {
	path := filepath.Join(projectDir, schemasDir, resource+".json")

	schema, err := os.ReadFile(path) //nolint:gosec // G304: resource comes from the probe table, not input
	if err != nil {
		return nil, fmt.Errorf("reading schema %s: %w", path, err)
	}

	return schema, nil
}

// unknownKeys reports payload object keys that the schema does not declare. The schemas do not set
// additionalProperties:false, so validation ignores fields motorsportstats adds — a rename shows up as
// one unknown key here plus one missing required field in the connector's own validation.
//
// Paths are reported as "/details[]/drivers[]: nationalityCode", deduplicated so a new field on all
// twenty cars in a classification is reported once rather than twenty times.
func unknownKeys(schema []byte, payload []byte) ([]string, error) {
	var schemaNode any
	if err := json.Unmarshal(schema, &schemaNode); err != nil {
		return nil, fmt.Errorf("unmarshalling schema: %w", err)
	}

	var payloadNode any
	if err := json.Unmarshal(payload, &payloadNode); err != nil {
		return nil, fmt.Errorf("unmarshalling payload: %w", err)
	}

	found := map[string]map[string]struct{}{}
	walkForUnknownKeys(schemaNode, payloadNode, "", found)

	paths := make([]string, 0, len(found))
	for path := range found {
		paths = append(paths, path)
	}
	sort.Strings(paths)

	report := make([]string, 0, len(paths))
	for _, path := range paths {
		keys := make([]string, 0, len(found[path]))
		for key := range found[path] {
			keys = append(keys, key)
		}
		sort.Strings(keys)

		report = append(report, fmt.Sprintf("%s: %s", displayPath(path), strings.Join(keys, ", ")))
	}

	return report, nil
}

// displayPath renders the root — "" for an object payload, "[]" for an array one — as a leading slash,
// so every reported path reads the same way.
func displayPath(path string) string {
	if strings.HasPrefix(path, "/") {
		return path
	}

	return "/" + path
}

// walkForUnknownKeys descends the schema and the payload in step, collecting keys per path. A schema
// node that says nothing about the shape below it simply stops the descent — silence in the schema is
// not evidence of an unknown key.
func walkForUnknownKeys(schemaNode any, payloadNode any, path string, found map[string]map[string]struct{}) {
	schemaObject, isObject := schemaNode.(map[string]any)
	if isObject == false {
		return
	}

	if payloadList, isList := payloadNode.([]any); isList {
		items, hasItems := schemaObject["items"]
		if hasItems == false {
			return
		}

		for _, payloadItem := range payloadList {
			walkForUnknownKeys(items, payloadItem, path+"[]", found)
		}

		return
	}

	payloadObject, isPayloadObject := payloadNode.(map[string]any)
	if isPayloadObject == false {
		return
	}

	properties, hasProperties := schemaObject["properties"].(map[string]any)
	if hasProperties == false {
		return
	}

	for key, value := range payloadObject {
		property, declared := properties[key]
		if declared == false {
			if _, seen := found[path]; seen == false {
				found[path] = map[string]struct{}{}
			}

			found[path][key] = struct{}{}

			continue
		}

		walkForUnknownKeys(property, value, path+"/"+key, found)
	}
}
