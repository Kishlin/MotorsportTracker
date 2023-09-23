<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Season;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeriesGateway;
use Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject\SeriesIdentity;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared\EntityStoreSpy;

final readonly class SeriesGatewayStub implements SeriesGateway
{
    public function __construct(
        private EntityStoreSpy $entityStoreSpy,
    ) {
    }

    public function find(string $seriesName): ?SeriesIdentity
    {
        foreach ($this->entityStoreSpy->stored('series') as $series) {
            if ($series['name'] === $seriesName) {
                return SeriesIdentity::forScalars($series['id'], $series['ref']);
            }
        }

        return null;
    }
}
