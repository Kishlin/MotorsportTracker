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

var calendar = map[string]string{
	"71fdf79a-0cf3-4aab-99f6-b9a836c333da": `{"season":{"name":"2025 World Championship","uuid":"71fdf79a-0cf3-4aab-99f6-b9a836c333da","year":2025,"endYear":2025},"events":[{"uuid":"3076d943-d03f-4e2a-8518-64e852c927d1","name":"Australian Grand Prix","shortName":"Australian GP","shortCode":"AUS","status":"","startDate":1741910400,"startTimeUtc":1741870800,"endDate":1742083200,"endTimeUtc":1742130000,"venue":{"name":"Melbourne Grand Prix Circuit","uuid":"cc0117d2-7b7a-46f9-9714-7a336cdf960e","shortName":"Albert Park","shortCode":"MEL","picture":null},"country":{"name":"Australia","uuid":"5d247133-491d-4589-ab00-13d66211768b","picture":"https://assets.motorsportstats.com/flags/svg/au.svg"},"sessions":[{"uuid":"49e1cec7-b721-4ae0-b64e-f6e56f57d42a","name":"1st Free Practice","shortName":"Practice 1","shortCode":"P1","status":"","hasResults":true,"startTime":1741955400,"startTimeUtc":1741915800,"endTime":1741959000,"endTimeUtc":1741919400},{"uuid":"901416d0-ab91-40e3-88ba-28e20c0aad87","name":"2nd Free Practice","shortName":"Practice 2","shortCode":"P2","status":"","hasResults":true,"startTime":1741968000,"startTimeUtc":1741928400,"endTime":1741971600,"endTimeUtc":1741932000},{"uuid":"5bd0d80f-d7e4-43b1-88c9-30a98d8deb44","name":"3rd Free Practice","shortName":"Practice 3","shortCode":"P3","status":"","hasResults":true,"startTime":1742041800,"startTimeUtc":1742002200,"endTime":1742045400,"endTimeUtc":1742005800},{"uuid":"ff2bda66-24f0-4837-8b8b-30ca0a955b6b","name":"1st Qualifying","shortName":"Qualifying 1","shortCode":"Q1","status":"","hasResults":true,"startTime":1742054400,"startTimeUtc":1742014800,"endTime":1742055480,"endTimeUtc":1742015880},{"uuid":"e4b0e551-ccdb-4569-bfef-e914f3e57691","name":"2nd Qualifying","shortName":"Qualifying 2","shortCode":"Q2","status":"","hasResults":true,"startTime":1742055900,"startTimeUtc":1742016300,"endTime":1742056800,"endTimeUtc":1742017200},{"uuid":"8dca3ccd-2c3b-49c7-bc29-2c4cd7bb812c","name":"3rd Qualifying","shortName":"Qualifying 3","shortCode":"Q3","status":"","hasResults":true,"startTime":1742057280,"startTimeUtc":1742017680,"endTime":1742058000,"endTimeUtc":1742018400},{"uuid":"5f27995f-c280-4b5d-9f15-9978cbe936bc","name":"Combined Qualifying","shortName":"Qualifying","shortCode":"Q","status":"","hasResults":true,"startTime":1741996800,"startTimeUtc":1741957200,"endTime":null,"endTimeUtc":null},{"uuid":"6c24d47d-cb93-48e7-afbf-6a0389b829e4","name":"Race","shortName":"Race","shortCode":"Race","status":"","hasResults":true,"startTime":1742137200,"startTimeUtc":1742097600,"endTime":1742144400,"endTimeUtc":1742104800}]},{"uuid":"a45be211-6121-4b88-834e-ba447d2eb0b7","name":"Chinese Grand Prix","shortName":"China GP","shortCode":"CHN","status":"","startDate":1742515200,"startTimeUtc":1742486400,"endDate":1742688000,"endTimeUtc":1742745600,"venue":{"name":"Shanghai International Circuit","uuid":"adb703d8-45ea-4dcc-a13f-a48a5f652748","shortName":"Shanghai","shortCode":"SHA","picture":null},"country":{"name":"China","uuid":"c08bdf58-c105-4902-8ce4-3334f189d136","picture":"https://assets.motorsportstats.com/flags/svg/cn.svg"},"sessions":[{"uuid":"2137c4a7-5922-4c94-9a44-9d3cc07f86c9","name":"1st Free Practice","shortName":"Practice 1","shortCode":"P1","status":"","hasResults":true,"startTime":1742556600,"startTimeUtc":1742527800,"endTime":1742560200,"endTimeUtc":1742531400},{"uuid":"10ada0a2-9d68-4b5c-9ca8-c8d8e7e3aef5","name":"1st Sprint Qualifying","shortName":"Sprint Shootout 1","shortCode":"SQ1","status":"","hasResults":true,"startTime":1742571000,"startTimeUtc":1742542200,"endTime":1742571720,"endTimeUtc":1742542920},{"uuid":"253eb214-fda7-4f5e-8a50-642997d148d8","name":"2nd Sprint Qualifying","shortName":"Sprint Shootout 2","shortCode":"SQ2","status":"","hasResults":true,"startTime":1742572200,"startTimeUtc":1742543400,"endTime":1742572800,"endTimeUtc":1742544000},{"uuid":"8e4667ed-19c5-40c8-aa1d-38fc09b3a6c8","name":"3rd Sprint Qualifying","shortName":"Sprint Shootout 3","shortCode":"SQ3","status":"","hasResults":true,"startTime":1742573160,"startTimeUtc":1742544360,"endTime":1742573640,"endTimeUtc":1742544840},{"uuid":"e76fa061-8b74-494e-a6c7-024d5fcc761c","name":"Combined Sprint Qualifying","shortName":"Sprint Shootout","shortCode":"SQ","status":"","hasResults":true,"startTime":1742515200,"startTimeUtc":1742486400,"endTime":null,"endTimeUtc":null},{"uuid":"f50563fa-5a19-41eb-87bf-a5913d8ac229","name":"Sprint","shortName":"Sprint","shortCode":"SR","status":"","hasResults":true,"startTime":1742641200,"startTimeUtc":1742612400,"endTime":1742643000,"endTimeUtc":1742614200},{"uuid":"eeccc2d1-bed3-4af2-848b-5f2c2079624c","name":"1st Qualifying","shortName":"Qualifying 1","shortCode":"Q1","status":"","hasResults":true,"startTime":1742655600,"startTimeUtc":1742626800,"endTime":1742656680,"endTimeUtc":1742627880},{"uuid":"2cb0f2c0-7a34-405a-b851-7cd23f6aba8f","name":"2nd Qualifying","shortName":"Qualifying 2","shortCode":"Q2","status":"","hasResults":true,"startTime":1742657100,"startTimeUtc":1742628300,"endTime":1742658000,"endTimeUtc":1742629200},{"uuid":"e22e9c9b-8262-4029-8df2-4044238a4fc9","name":"3rd Qualifying","shortName":"Qualifying 3","shortCode":"Q3","status":"","hasResults":true,"startTime":1742658480,"startTimeUtc":1742629680,"endTime":1742659200,"endTimeUtc":1742630400},{"uuid":"0d4154cc-2b05-4882-ad65-c97ec24a3c48","name":"Combined Qualifying","shortName":"Qualifying","shortCode":"Q","status":"","hasResults":true,"startTime":1742601600,"startTimeUtc":1742572800,"endTime":null,"endTimeUtc":null},{"uuid":"443b426f-cc54-4d42-a05a-2da3c3059e26","name":"Race","shortName":"Race","shortCode":"Race","status":"","hasResults":true,"startTime":1742742000,"startTimeUtc":1742713200,"endTime":1742749200,"endTimeUtc":1742720400}]}]}`,
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
	newRoute("/widgets/1.0.0/seasons/([^/]+)/calendar", calendarHandler),
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

func calendarHandler(w http.ResponseWriter, r *http.Request) {
	seasonUUID := getField(r, 0)

	data, exists := calendar[seasonUUID]
	if !exists {
		http.Error(w, "Season not found", http.StatusNotFound)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(http.StatusOK)
	_, _ = w.Write([]byte(data))
}
