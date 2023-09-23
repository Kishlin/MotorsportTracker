<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\ScrapSeasonsCommandHandler;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeasonsExtractor;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeasonsTransformer;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeriesGateway;
use Kishlin\Backend\MotorsportETL\Season\Infrastructure\SeasonsExtractorUsingMotorsportStatsConnector;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Season\SeriesGatewayStub;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared\EntityStoreSpy;

trait SeasonServicesTrait
{
    private ?SeriesGateway $seriesGateway = null;

    private ?SeasonsExtractor $seasonsExtractor = null;

    private ?ScrapSeasonsCommandHandler $scrapSeasonsCommandHandler = null;

    abstract public function loader(): Loader;

    abstract public function connector(): Connector;

    abstract public function entityStoreSpy(): EntityStoreSpy;

    abstract public function cacheInvalidatorGateway(): CacheInvalidatorGateway;

    abstract public function jsonableStringTransformer(): JsonableStringTransformer;

    public function seriesGateway(): SeriesGateway
    {
        if (null === $this->seriesGateway) {
            $this->seriesGateway = new SeriesGatewayStub(
                $this->entityStoreSpy(),
            );
        }

        return $this->seriesGateway;
    }

    public function seasonsExtractor(): SeasonsExtractor
    {
        if (null === $this->seasonsExtractor) {
            $this->seasonsExtractor = new SeasonsExtractorUsingMotorsportStatsConnector(
                $this->connector(),
            );
        }

        return $this->seasonsExtractor;
    }

    public function scrapSeasonsCommandHandler(): ScrapSeasonsCommandHandler
    {
        if (null === $this->scrapSeasonsCommandHandler) {
            $seasonsTransformer = new SeasonsTransformer(
                $this->jsonableStringTransformer(),
            );

            $this->scrapSeasonsCommandHandler = new ScrapSeasonsCommandHandler(
                $this->cacheInvalidatorGateway(),
                $seasonsTransformer,
                $this->seasonsExtractor(),
                $this->seriesGateway(),
                $this->loader(),
            );
        }

        return $this->scrapSeasonsCommandHandler;
    }
}
