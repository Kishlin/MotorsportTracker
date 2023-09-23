<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingData;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingsIdentity;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class StandingDriver extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly StandingsIdentity $standingsIdentity,
        private readonly Driver $driver,
        private readonly ?Country $countryRepresenting,
        private readonly StandingData $standingData,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'identity' => $this->standingsIdentity,
            'standee'  => $this->driver,
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
     *     driver: array{name: string, shortCode: string, uuid: string},
     *     nationality: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     * } $standing
     */
    public static function fromData(string $season, ?string $seriesClass, array $standing): self
    {
        $country = null === $standing['nationality'] ? null : Country::fromData($standing['nationality']);

        return new self(
            StandingsIdentity::fromData($season, $seriesClass),
            Driver::fromData($standing['driver'], $country),
            $country,
            StandingData::fromData($standing['position'], $standing['points']),
        );
    }
}
