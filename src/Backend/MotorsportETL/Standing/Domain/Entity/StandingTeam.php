<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingData;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingsIdentity;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class StandingTeam extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly StandingsIdentity $standingsIdentity,
        private readonly Team $team,
        private readonly ?Country $countryRepresenting,
        private readonly StandingData $standingData,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'identity' => $this->standingsIdentity,
            'standee'  => $this->team,
            'country'  => $this->countryRepresenting,
            'data'     => $this->standingData,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['season', 'series_class', 'standee', 'position'],
        ];
    }

    /**
     * @param array{
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     icon: string,
     *     countryRepresenting: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     * } $standing
     */
    public static function fromData(string $season, ?string $seriesClass, array $standing): self
    {
        $country = null === $standing['countryRepresenting'] ? null : Country::fromData($standing['countryRepresenting']);

        return new self(
            StandingsIdentity::fromData($season, $seriesClass),
            Team::fromData($season, $standing['team']),
            $country,
            StandingData::fromData($standing['position'], $standing['points']),
        );
    }
}
