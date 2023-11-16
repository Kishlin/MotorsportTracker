<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Team;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\AnalyticsIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingData;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\TeamAnalyticsData;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class AnalyticsTeams extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly AnalyticsIdentity $analyticsIdentity,
        private readonly Team $team,
        private readonly ?Country $countryRepresenting,
        private readonly StandingData $standingData,
        private readonly TeamAnalyticsData $analyticsData,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'identity'  => $this->analyticsIdentity,
            'team'      => $this->team,
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
            ['season', 'team', 'position'],
        ];
    }

    /**
     * @param array{
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     icon: string,
     *     countryRepresenting: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         classWins: int,
     *         fastestLaps: int,
     *         finalAppearances: int,
     *         finishes1And2: int,
     *         podiums: int,
     *         poles: int,
     *         qualifies1And2: int,
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
        $country = null === $standing['countryRepresenting'] ? null : Country::fromData($standing['countryRepresenting']);

        return new self(
            AnalyticsIdentity::fromData($season),
            Team::fromData($season, $standing['team']),
            $country,
            StandingData::fromData($standing['position'], $standing['points']),
            TeamAnalyticsData::fromData($standing['analytics']),
        );
    }
}
