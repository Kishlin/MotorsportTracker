<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared;

use Kishlin\Backend\MotorsportETL\Shared\Domain\SeasonGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonFilter;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;

final readonly class SeasonGatewayStub implements SeasonGateway
{
    public function __construct(
        private EntityStoreSpy $entityStoreSpy,
    ) {
    }

    public function find(SeasonFilter $filter): ?SeasonIdentity
    {
        $seriesId = null;
        foreach ($this->entityStoreSpy->stored('series') as $series) {
            if ($series['name'] === $filter->seriesName()) {
                $seriesId = $series['id'];
            }
        }

        if (null === $seriesId) {
            return null;
        }

        foreach ($this->entityStoreSpy->stored('season') as $season) {
            if ($season['series'] === $seriesId && $season['year'] === $filter->year()) {
                return SeasonIdentity::forScalars((string) $season['id'], (string) $season['ref']);
            }
        }

        return null;
    }
}
