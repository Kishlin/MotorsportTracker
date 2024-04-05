<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Analytics;

use JsonException;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\ConstructorAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\ObjectStoreSpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\SeasonFinderTrait;

final readonly class ConstructorAnalyticsForSeasonRepositoryStub implements ConstructorAnalyticsForSeasonGateway
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
        $seasonId = $this->findSeasonIdOrStop($championship, $year);
        $data     = [];

        foreach ($this->objectStore->all('analytics_constructors') as $analytics) {
            if ($seasonId !== $analytics['season']) {
                continue;
            }

            $data[] = [
                'id'          => $analytics['id'],
                'constructor' => $analytics['constructor'],
                'country'     => "{\"id\":\"{$analytics['country']}\"}",
            ];
        }

        return AnalyticsForSeasonDTO::fromData($data);
    }
}
