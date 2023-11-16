<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class ConstructorTeam extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly Constructor $constructor,
        private readonly Team $team,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'constructor' => $this->constructor,
            'team'        => $this->team,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['constructor', 'team'],
        ];
    }

    /**
     * @param array{
     *     constructor: array{name: string, uuid: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *  } $standing
     */
    public static function fromData(string $season, array $standing): self
    {
        return new self(
            Constructor::fromData($standing['constructor']),
            Team::fromData($season, $standing['team']),
        );
    }
}
