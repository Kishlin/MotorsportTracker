# Frontend

Rules for `apps/MotorsportTracker/Frontend/`. Loaded on top of the root `CLAUDE.md`.

Next.js 15 (App Router) · React 18 · TypeScript · MUI 5 with Emotion · ApexCharts · Jest + Testing Library · ESLint (airbnb + next).

## The Make targets for this app are broken

`make eslint` and `make tests.frontend` exec into a `frontend` container; `make frontend.sh` and `make frontend.build` use a `node` container. **Neither service exists** — the `frontend` service is commented out in `docker-compose.yaml.dist` (line 110), and `node` is not defined at all. Active services are `sqs`, `postgres`, `golang`, `docker-app`, `cache`.

Consequence: `make tests` fails on its frontend half, and `make complete-analysis` fails too.

Run npm directly in this directory instead:

```bash
npm run dev     # next dev
npm run build   # next build
npm run test    # jest
npm run lint    # next lint
```

Uncommenting the compose service is the alternative — but do not "fix" a failing `make eslint` by assuming the config is wrong. The container is simply absent.

## Layout

| Path | Contents |
|---|---|
| `app/` | App Router pages and layouts |
| `src/MotorsportTracker/` | Feature components |
| `src/MotorsportGraph/` | Data visualisation — tyre history, race pace, fastest laps |
| `src/Canvas/` | Canvas rendering primitives |
| `src/Shared/` | Cross-feature utilities |

## Tests

Colocated in `__tests__/` directories beside the code under test — see `src/MotorsportTracker/Schedule/Utils/Date/__tests__`. Jest with `ts-jest`, Testing Library for components. Query by role and accessible name rather than test IDs.

Coverage is currently thin and concentrated in `Schedule/Utils`. New utility logic should arrive with tests; do not treat the sparse existing coverage as the standard to match.

## Rendering model

Standings pages are statically generated and served from Next's own cache. Upcoming-event pages are server-rendered per request and read through the API. Changing which bucket a page falls into changes when its data goes stale — and the update flow depends on an explicit cache invalidation step, so a page moved to SSG silently stops reflecting new scrapes until that runs.

## Style

ESLint extends airbnb plus `next/core-web-vitals`; `.eslintrc.json` is the source of truth and airbnb is stricter than most defaults. Run `npm run lint` before considering frontend work finished — the Go hooks do not cover this directory.

MUI components and the `sx` prop over bespoke CSS. Emotion is already configured; do not introduce a second styling system.
