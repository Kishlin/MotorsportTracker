package registration

import (
	"context"
	"fmt"

	dependencyinjection "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/dependencyinjection/infrastructure"
	calendar "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/calendar/domain"
	calendarImpls "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/calendar/infrastructure"
	classification "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/classification/domain"
	classificationImpls "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/classification/infrastructure"
	seasons "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/domain"
	seasonsImpls "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/infrastructure"
	series "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series/domain"
	seriesImpls "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series/infrastructure"
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

// RegisterAllHandlers registers all scraping handlers with the provided HandlersList.
func RegisterAllHandlers(ctx context.Context, handlersList *messaging.HandlersList, registry *dependencyinjection.ServicesRegistry) {
	registerSeriesHandlers(ctx, handlersList, registry)
	registerSeasonsHandlers(ctx, handlersList, registry)
	registerCalendarHandlers(ctx, handlersList, registry)
	registerClassificationHandlers(ctx, handlersList, registry)
}

// GetIntent returns the intent for a given subcommand name.
func GetIntent(name string) (application.Intent, error) {
	switch name {
	case seriesImpls.ScrapeSeriesIntentName:
		return seriesImpls.NewScrapSeriesIntent(), nil
	case seasonsImpls.ScrapeSeasonsForSeriesKeywordIntentName:
		return seasonsImpls.NewScrapeSeasonsForSeriesKeywordIntent(), nil
	case seasonsImpls.ScrapeSeasonsForSeriesIDIntentName:
		return seasonsImpls.NewScrapeSeasonsForSeriesIDIntent(), nil
	case seasonsImpls.ScrapeSeasonsForAllSeriesIntentName:
		return seasonsImpls.NewScrapeSeasonsForAllSeriesIntent(), nil
	case calendarImpls.ScrapeCalendarIntentName:
		return calendarImpls.NewScrapCalendarIntent(), nil
	case classificationImpls.ScrapeClassificationIntentName:
		return classificationImpls.NewScrapClassificationIntent(), nil
	default:
		return nil, fmt.Errorf("unknown subcommand: %s", name)
	}
}

func registerSeriesHandlers(ctx context.Context, handlersList *messaging.HandlersList, registry *dependencyinjection.ServicesRegistry) {
	handlersList.RegisterHandler(
		seriesImpls.ScrapeSeriesIntentName,
		seriesImpls.NewScrapeSeriesHandler(
			series.NewScrapeSeriesUseCase(
				registry.GetMotorsportStatsGateway(ctx),
				seriesImpls.NewSaveSeriesRepository(registry.GetCoreDatabase(ctx)),
			),
		),
	)
}

func registerSeasonsHandlers(ctx context.Context, handlersList *messaging.HandlersList, registry *dependencyinjection.ServicesRegistry) {
	scrapeSeasonsForSeriesIdentifierUseCase := seasons.NewScrapeSeasonsForSeriesIdentifierUseCase(
		registry.GetMotorsportStatsGateway(ctx),
		seasonsImpls.NewSaveSeasonsRepository(registry.GetCoreDatabase(ctx)),
	)

	handlersList.RegisterHandler(
		seasonsImpls.ScrapeSeasonsForSeriesKeywordIntentName,
		seasonsImpls.NewScrapeSeasonsForSeriesKeywordHandler(
			seasons.NewScrapeSeasonsForSeriesKeywordUseCase(
				scrapeSeasonsForSeriesIdentifierUseCase,
				seasonsImpls.NewSearchSeriesIdentifierRepository(registry.GetCoreDatabase(ctx)),
			),
		),
	)

	handlersList.RegisterHandler(
		seasonsImpls.ScrapeSeasonsForSeriesIDIntentName,
		seasonsImpls.NewScrapeSeasonsForSeriesIDHandler(scrapeSeasonsForSeriesIdentifierUseCase),
	)

	handlersList.RegisterHandler(
		seasonsImpls.ScrapeSeasonsForAllSeriesIntentName,
		seasonsImpls.NewScrapeSeasonsForAllSeriesHandler(
			seasons.NewScrapeSeasonsForAllSeriesUseCase(
				seasonsImpls.NewSearchAllSeriesIdentifiersRepository(registry.GetCoreDatabase(ctx)),
				seasonsImpls.NewSeasonsScrapper(registry.GetIntentsQueue()),
			),
		),
	)
}

func registerCalendarHandlers(ctx context.Context, handlersList *messaging.HandlersList, registry *dependencyinjection.ServicesRegistry) {
	handlersList.RegisterHandler(
		calendarImpls.ScrapeCalendarIntentName,
		calendarImpls.NewScrapeCalendarHandler(
			calendar.NewScrapeCalendarUseCase(
				registry.GetMotorsportStatsGateway(ctx),
				calendarImpls.NewSaveCalendarRepository(registry.GetCoreDatabase(ctx)),
				calendarImpls.NewSearchSeasonIdentifierRepository(registry.GetCoreDatabase(ctx)),
			),
		),
	)
}

func registerClassificationHandlers(ctx context.Context, handlersList *messaging.HandlersList, registry *dependencyinjection.ServicesRegistry) {
	handlersList.RegisterHandler(
		classificationImpls.ScrapeClassificationIntentName,
		classificationImpls.NewScrapeClassificationHandler(
			classification.NewScrapeClassificationUseCase(
				registry.GetMotorsportStatsGateway(ctx),
				classificationImpls.NewSearchSessionIdentifierRepository(registry.GetCoreDatabase(ctx)),
				classificationImpls.NewSaveClassificationRepository(registry.GetCoreDatabase(ctx)),
			),
		),
	)
}
