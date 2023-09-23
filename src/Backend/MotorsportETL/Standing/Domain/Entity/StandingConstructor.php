<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingData;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingsIdentity;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class StandingConstructor extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly StandingsIdentity $standingsIdentity,
        private readonly Constructor $constructor,
        private readonly ?Country $countryRepresenting,
        private readonly StandingData $standingData,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'identity' => $this->standingsIdentity,
            'standee'  => $this->constructor,
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
     *      constructor: array{name: string, uuid: string},
     *      countryRepresenting: array{name: string, uuid: string, picture: string}|null,
     *      position: int,
     *      points: float,
     *  } $standing
     */
    public static function fromData(string $season, ?string $seriesClass, array $standing): self
    {
        $country = null === $standing['countryRepresenting'] ? null : Country::fromData($standing['countryRepresenting']);

        return new self(
            StandingsIdentity::fromData($season, $seriesClass),
            Constructor::fromData($standing['constructor']),
            $country,
            StandingData::fromData($standing['position'], $standing['points']),
        );
    }
}
