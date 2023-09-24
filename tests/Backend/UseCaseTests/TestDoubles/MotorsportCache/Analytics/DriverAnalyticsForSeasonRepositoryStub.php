<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Analytics;

use JsonException;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\DriverAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\ObjectStoreSpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\SeasonFinderTrait;

final readonly class DriverAnalyticsForSeasonRepositoryStub implements DriverAnalyticsForSeasonGateway
{
    use SeasonFinderTrait;

    public function __construct(
        private ObjectStoreSpy $objectStore,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function find(string $championship, int $year): AnalyticsForSeasonDTO
    {
        $data   = [];
        $season = $this->findSeasonOrStop($championship, $year);

        /** @var AnalyticsDrivers $analytics */
        foreach ($this->objectStore->all(AnalyticsDrivers::class) as $analytics) {
            if (false === $analytics->season()->equals($season->id())) {
                continue;
            }

            $data[] = [
                'id'      => $analytics->id()->value(),
                'driver'  => $analytics->driver()->value(),
                'country' => "{\"id\":\"{$analytics->country()->value()}\"}",
            ];
        }

        return AnalyticsForSeasonDTO::fromData($data);
    }
}
