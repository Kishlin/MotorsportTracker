package infrastructure

import (
	"context"
	"net/http"
	"net/http/httptest"
	"regexp"

	client "github.com/kishlin/MotorsportTracker/src/Golang/shared/client/infrastructure"
)

const series = `[{"name":"FIA Formula One World Championship","uuid":"a33f8b4a-2b22-41ce-8e7d-0aea08f0e176","shortName":"Formula 1","shortCode":"F1","category":"Single Seater"},{"name":"FIA World Endurance Championship","uuid":"967cd5ab-5562-40dc-a0b0-109738adcd01","shortName":"World Endurance Cham","shortCode":"WEC","category":"Sportscar"},{"name":"FIM MotoGP World Championship","uuid":"a485d01c-f907-4ff7-83db-ca1c90cc28a1","shortName":null,"shortCode":"MotoGP","category":"Motorcycle"}]`

var seasons = map[string]string{
	"a33f8b4a-2b22-41ce-8e7d-0aea08f0e176": `[{"name":"2025 World Championship","uuid":"71fdf79a-0cf3-4aab-99f6-b9a836c333da","year":2025,"endYear":2025,"status":""},{"name":"2024 World Championship","uuid":"afc5ab1b-c44b-4c21-904d-215fcc800578","year":2024,"endYear":2024,"status":"Current"},{"name":"2023 World Championship","uuid":"bbe13c43-7cc2-4cca-90a0-9268e98ff6e3","year":2023,"endYear":2023,"status":"Historic"}]`,
	"967cd5ab-5562-40dc-a0b0-109738adcd01": `[{"name":"2025 World Endurance Championship","uuid":"204926ca-77b0-4a4f-a1e7-352152e4fc25","year":2025,"endYear":2025,"status":"Current"},{"name":"2024 World Endurance Championship","uuid":"57eeab02-e71c-412d-aec6-e524ac622d63","year":2024,"endYear":2024,"status":"Historic"}]`,
	"a485d01c-f907-4ff7-83db-ca1c90cc28a1": `[{"name":"2025 World Championship","uuid":"52773acf-a477-409a-9d23-e31bd26e25f1","year":2025,"endYear":2025,"status":"Current"},{"name":"2024 World Championship","uuid":"6135c23f-bb6e-4b2b-a276-cc9011a424fa","year":2024,"endYear":2024,"status":"Historic"}]`,
}

func NewTestServer() *httptest.Server {
	return httptest.NewServer(
		http.HandlerFunc(serve),
	)
}

func ConnectorForTestServer(server *httptest.Server) *ConnectorUsingClient {
	return NewConnectorUsingClient(client.NewClient(server.URL))
}

func serve(w http.ResponseWriter, r *http.Request) {
	if r.Method != "GET" {
		http.Error(w, "Method not allowed", http.StatusMethodNotAllowed)
		return
	}

	if r.Header.Get("Origin") != "https://widgets.motorsportstats.com" ||
		r.Header.Get("X-Parent-Referer") != "https://motorsportstats.com/" {
		http.Error(w, "Origin not allowed", http.StatusForbidden)
		return
	}

	for _, route := range routes {
		matches := route.regex.FindSubmatch([]byte(r.URL.Path))
		if len(matches) > 0 {
			ctx := context.WithValue(r.Context(), ctxKey{}, matches[1:])
			route.handler(w, r.WithContext(ctx))
			return
		}
	}
}

var routes = []route{
	newRoute("/widgets/1.0.0/series", seriesHandler),
	newRoute("/widgets/1.0.0/series/([^/]+)/seasons", seasonsHandler),
}

func newRoute(pattern string, handler http.HandlerFunc) route {
	return route{regexp.MustCompile("^" + pattern + "$"), handler}
}

type route struct {
	regex   *regexp.Regexp
	handler http.HandlerFunc
}

type ctxKey struct{}

func getField(r *http.Request, index int) string {
	fields, ok := r.Context().Value(ctxKey{}).([][]byte)
	if !ok || index >= len(fields) {
		return ""
	}

	return string(fields[index])
}

func seriesHandler(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(http.StatusOK)
	_, _ = w.Write([]byte(series))
}

func seasonsHandler(w http.ResponseWriter, r *http.Request) {
	seriesUUID := getField(r, 0)

	data, exists := seasons[seriesUUID]
	if !exists {
		http.Error(w, "Series not found", http.StatusNotFound)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(http.StatusOK)
	_, _ = w.Write([]byte(data))
}
