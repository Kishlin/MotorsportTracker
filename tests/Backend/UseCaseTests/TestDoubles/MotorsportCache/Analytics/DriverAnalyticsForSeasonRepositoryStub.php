<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Analytics;

use JsonException;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache\DriverAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\ObjectStoreSpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\SeasonFinderTrait;

final readonly class DriverAnalyticsForSeasonRepositoryStub implements DriverAnalyticsForSeasonGateway
{
    use SeasonFinderTrait;

    public function __construct(
        private ObjectStoreSpy $objectStore,
    ) {}

    /**
     * @throws JsonException
     */
    public function find(string $championship, int $year): AnalyticsForSeasonDTO
    {
        $seasonId = $this->findSeasonIdOrStop($championship, $year);
        $data     = [];

        foreach ($this->objectStore->all('analytics_drivers') as $analytics) {
            if ($seasonId !== $analytics['season']) {
                continue;
            }

            $data[] = [
                'id'      => $analytics['id'],
                'driver'  => $analytics['driver'],
                'country' => "{\"id\":\"{$analytics['country']}\"}",
            ];
        }

        return AnalyticsForSeasonDTO::fromData($data);
    }
}
