<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Analytics;

use JsonException;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\ConstructorAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsConstructors;
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
        $data   = [];
        $season = $this->findSeasonOrStop($championship, $year);

        /** @var AnalyticsConstructors $analytics */
        foreach ($this->objectStore->all(AnalyticsConstructors::class) as $analytics) {
            if (false === $analytics->season()->equals($season->id())) {
                continue;
            }

            $data[] = [
                'id'          => $analytics->id()->value(),
                'constructor' => $analytics->constructor()->value(),
                'country'     => "{\"id\":\"{$analytics->country()->value()}\"}",
            ];
        }

        return AnalyticsForSeasonDTO::fromData($data);
    }
}
