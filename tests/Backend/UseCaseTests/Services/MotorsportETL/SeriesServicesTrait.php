<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL;

use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\ScrapSeriesListCommandHandler;
use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\SeriesCacheInvalidator;
use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\SeriesListExtractor;
use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\SeriesListTransformer;
use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\TransformerSteps\MessedUpSeriesFixer;
use Kishlin\Backend\MotorsportETL\Series\Infrastructure\SeriesCacheInvalidatorUsingRepository;
use Kishlin\Backend\MotorsportETL\Series\Infrastructure\SeriesListExtractorUsingMotorsportStatsConnector;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;

trait SeriesServicesTrait
{
    private ?SeriesCacheInvalidator $seriesCacheInvalidator = null;

    private ?SeriesListExtractor $seriesListExtractor = null;

    private ?ScrapSeriesListCommandHandler $scrapSeriesListCommandHandler = null;

    abstract public function loader(): Loader;

    abstract public function connector(): Connector;

    abstract public function cacheInvalidatorGateway(): CacheInvalidatorGateway;

    abstract public function jsonableStringTransformer(): JsonableStringTransformer;

    public function seriesCacheInvalidator(): SeriesCacheInvalidator
    {
        if (null === $this->seriesCacheInvalidator) {
            $this->seriesCacheInvalidator = new SeriesCacheInvalidatorUsingRepository(
                $this->cacheInvalidatorGateway(),
            );
        }

        return $this->seriesCacheInvalidator;
    }

    public function seriesListExtractor(): SeriesListExtractor
    {
        if (null === $this->seriesListExtractor) {
            $this->seriesListExtractor = new SeriesListExtractorUsingMotorsportStatsConnector(
                $this->connector(),
            );
        }

        return $this->seriesListExtractor;
    }

    public function scrapSeriesListCommandHandler(): ScrapSeriesListCommandHandler
    {
        if (null === $this->scrapSeriesListCommandHandler) {
            $seriesTransformer = new SeriesListTransformer(
                [new MessedUpSeriesFixer()],
                $this->jsonableStringTransformer(),
            );

            $this->scrapSeriesListCommandHandler = new ScrapSeriesListCommandHandler(
                $this->seriesCacheInvalidator(),
                $seriesTransformer,
                $this->seriesListExtractor(),
                $this->loader(),
            );
        }

        return $this->scrapSeriesListCommandHandler;
    }
}
