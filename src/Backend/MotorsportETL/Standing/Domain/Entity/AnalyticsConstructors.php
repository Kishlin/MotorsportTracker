<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\AnalyticsIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\ConstructorAnalyticsData;
use Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject\StandingData;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class AnalyticsConstructors extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly AnalyticsIdentity $analyticsIdentity,
        private readonly Constructor $constructor,
        private readonly ?Country $countryRepresenting,
        private readonly StandingData $standingData,
        private readonly ConstructorAnalyticsData $analyticsData,
    ) {}

    public function mappedData(): array
    {
        return [
            'identity'    => $this->analyticsIdentity,
            'constructor' => $this->constructor,
            'country'     => $this->countryRepresenting,
            'data'        => $this->standingData,
            'analytics'   => $this->analyticsData,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['season', 'constructor'],
        ];
    }

    /**
     * @param array{
     *     constructor: array{name: string, uuid: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string}|null,
     *     countryRepresenting: array{name: string, uuid: string, picture: string}|null,
     *     position: int,
     *     points: float,
     *     analytics: array{wins: int},
     * } $standing
     */
    public static function fromData(string $season, array $standing): self
    {
        $country = null === $standing['countryRepresenting'] ? null : Country::fromData($standing['countryRepresenting']);

        return new self(
            AnalyticsIdentity::fromData($season),
            Constructor::fromData($standing['constructor']),
            $country,
            StandingData::fromData($standing['position'], $standing['points']),
            ConstructorAnalyticsData::fromData($standing['analytics']),
        );
    }
}
