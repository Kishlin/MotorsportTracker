package scrapping

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/events"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/seasons"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/series"
)

func PopulateHandlers(handlersList *queue.HandlersList) {
	handlersList.RegisterHandler(series.ScrapSeriesMessageType, series.NewScrapSeriesHandler())
	handlersList.RegisterHandler(seasons.ScrapSeasonsMessageType, seasons.NewScrapSeasonsHandler())
	handlersList.RegisterHandler(events.ScrapEventsMessageType, events.NewScrapEventsHandler())
}
