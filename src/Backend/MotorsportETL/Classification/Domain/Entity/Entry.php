<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Entry extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly string $session,
        private readonly Driver $driver,
        private readonly Team $team,
        private readonly int $carNumber,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'session'    => $this->session,
            'driver'     => $this->driver,
            'team'       => $this->team,
            'car_number' => $this->carNumber,
        ];
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['session', 'car_number'],
            ['session', 'driver'],
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    /**
     * @param array{name: string, uuid: string, picture: ?string}                                 $country
     * @param array{name: string, shortCode: string, uuid: string}                                $driver
     * @param array{name: string, uuid: string, colour: string, picture: string, carIcon: string} $team
     */
    public static function fromData(
        string $session,
        string $season,
        array $country,
        array $driver,
        array $team,
        string $carNumber,
    ): self {
        return new self(
            $session,
            Driver::fromData($driver, Country::fromData($country)),
            Team::fromData($season, $team),
            (int) $carNumber,
        );
    }
}
