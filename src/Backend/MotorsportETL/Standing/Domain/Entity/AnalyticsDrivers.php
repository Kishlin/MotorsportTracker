<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\AnalyticsIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\DriverAnalyticsData;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingData;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class AnalyticsDrivers extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly AnalyticsIdentity $analyticsIdentity,
        private readonly Driver $driver,
        private readonly ?Country $countryRepresenting,
        private readonly StandingData $standingData,
        private readonly DriverAnalyticsData $analyticsData,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'identity'  => $this->analyticsIdentity,
            'driver'    => $this->driver,
            'country'   => $this->countryRepresenting,
            'data'      => $this->standingData,
            'analytics' => $this->analyticsData,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['season', 'driver', 'position'],
        ];
    }

    /**
     * @param array{
     *     driver: array{name: string, shortCode: string, uuid: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         avgFinishPosition: float,
     *         classWins: int,
     *         fastestLaps: int,
     *         finalAppearances: int,
     *         hatTricks: int,
     *         podiums: int,
     *         poles: int,
     *         racesLed: int,
     *         ralliesLed: int,
     *         retirements: int,
     *         semiFinalAppearances: int,
     *         stageWins: int,
     *         starts: int,
     *         top10s: int,
     *         top5s: int,
     *         wins: int,
     *         winsPercentage: float,
     *     },
     * } $standing
     */
    public static function fromData(string $season, array $standing): self
    {
        $country = null === $standing['nationality'] ? null : Country::fromData($standing['nationality']);

        return new self(
            AnalyticsIdentity::fromData($season),
            Driver::fromData($standing['driver'], $country),
            $country,
            StandingData::fromData($standing['position'], $standing['points']),
            DriverAnalyticsData::fromData($standing['analytics']),
        );
    }
}
