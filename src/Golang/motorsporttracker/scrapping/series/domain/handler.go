package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type ExistingSeriesRepository interface {
	GetExistingSeries(ctx context.Context) (map[string]*Series, error)
}

type SaveSeriesRepository interface {
	SaveSeries(ctx context.Context, series []*Series) error
}

type ScrapSeriesHandler struct {
	motorsportStatsGateway   motorsportstats.Gateway
	existingSeriesRepository ExistingSeriesRepository
	saveSeriesRepository     SaveSeriesRepository
}

// NewScrapSeriesHandler creates a new instance of ScrapSeriesHandler.
func NewScrapSeriesHandler(
	motorsportStatsGateway motorsportstats.Gateway,
	existingSeriesRepository ExistingSeriesRepository,
	saveSeriesRepository SaveSeriesRepository,
) *ScrapSeriesHandler {
	return &ScrapSeriesHandler{
		motorsportStatsGateway:   motorsportStatsGateway,
		existingSeriesRepository: existingSeriesRepository,
		saveSeriesRepository:     saveSeriesRepository,
	}
}

// Handle processes the scrapping intent for series.
func (h *ScrapSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
	fetchedSeries, err := h.motorsportStatsGateway.GetSeries(ctx)
	if err != nil {
		return fmt.Errorf("fetching series fetchedSeries: %w", err)
	}

	existingSeries, err := h.existingSeriesRepository.GetExistingSeries(ctx)
	if err != nil {
		return fmt.Errorf("fetching existing series: %w", err)
	}

	toInsert, stats := h.compare(fetchedSeries, existingSeries)

	slog.Info(
		"Series scrapping summary",
		"new_series_count",
		stats.newSeriesCount,
		"existing_series_count",
		stats.existingSeriesCount,
		"differing_series_count",
		stats.differingSeriesCount,
	)

	if len(toInsert) > 0 {
		if err := h.saveSeriesRepository.SaveSeries(ctx, toInsert); err != nil {
			return fmt.Errorf("saving new series: %w", err)
		}
		slog.Debug("New series inserted", "count", len(toInsert))
	} else {
		slog.Debug("No new series to insert")
	}

	return nil
}

type seriesComparisonStats struct {
	newSeriesCount, existingSeriesCount, differingSeriesCount int
}

func (h *ScrapSeriesHandler) compare(
	fetchedSeries []*motorsportstats.Series,
	existingSeries map[string]*Series,
) (
	toInsert []*Series,
	stats seriesComparisonStats,
) {
	for _, series := range fetchedSeries {
		castedFetchedSeries := (*Series)(series)

		if existingData, exists := existingSeries[series.UUID]; exists {
			stats.existingSeriesCount++
			slog.Debug("Series already exists", "name", series.Name, "uuid", series.UUID)

			// Compare data and log warnings if different
			if existingData.IsEqualTo(castedFetchedSeries) == false {
				stats.differingSeriesCount++
				slog.Warn("Series data differs", "name", series.Name, "uuid", series.UUID)
				h.logSeriesDifferences(castedFetchedSeries, existingData)
			}

			continue
		}

		toInsert = append(toInsert, castedFetchedSeries)
		stats.newSeriesCount++
		slog.Debug("New series to insert", "name", series.Name, "uuid", series.UUID)
	}

	return toInsert, stats
}

// logSeriesDifferences logs detailed differences between series data
func (h *ScrapSeriesHandler) logSeriesDifferences(newSeries, existingSeries *Series) {
	if newSeries.Name != existingSeries.Name {
		slog.Debug("Series names differ", "name", newSeries.Name, "name", existingSeries.Name)
	}
	if newSeries.ShortName != existingSeries.ShortName {
		slog.Debug("Series shortnames differ", "name", newSeries.ShortName, "name", existingSeries.ShortName)
	}
	if newSeries.ShortCode != existingSeries.ShortCode {
		slog.Debug("Series shortcodes differ", "name", newSeries.ShortName, "name", existingSeries.ShortName)
	}
	if newSeries.Category != existingSeries.Category {
		slog.Debug("Series categories differ", "category", newSeries.Category, "category", existingSeries.Category)
	}
}
