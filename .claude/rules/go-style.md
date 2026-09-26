---
paths:
  - "src/Golang/**/*.go"
  - "apps/Backend/**/*.go"
---

# Go Style

## Boolean comparisons

Compare booleans explicitly — never negate with `!`:

```go
if exists == false { … }   // yes
if !exists { … }           // no
```

This applies to every boolean expression, including struct fields and function results (`configOption.RequiresValue == false`). `make go-lint` enforces it through `scripts/negation-check.sh`.

## Errors

Return early on error, and wrap with `%w` so the chain survives: `return fmt.Errorf("saving fetched series: %w", err)`.

## Naming

Interfaces describe behavior (`Gateway`, `Cache`, `SeasonsScrapper`). Implementations describe their mechanism (`GatewayUsingConnector`, `ConnectorUsingClient`, `DatabaseCache`, `SeasonsScrapperUsingIntents`). Use cases describe the action (`ScrapeSeriesUseCase`).

## Idioms

- `fn.Deref(ptr, "")` for pointer defaults.
- `slog` with key/value pairs — `slog.Info("Saved series", "count", len(series))` — never `fmt.Sprintf` into the message.
- JSON struct tags are camelCase (`json:"shortName"`), matching the motorsportstats payloads.
