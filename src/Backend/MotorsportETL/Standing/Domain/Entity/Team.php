<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Team extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly string $season,
        private readonly string $name,
        private readonly ?string $color,
        private readonly string $ref,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'season' => $this->season,
            'name'   => $this->name,
            'color'  => $this->color,
            'ref'    => $this->ref,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['season', 'name', 'ref'],
        ];
    }

    /**
     * @param array{name: string, uuid: string, colour: string, picture: string, carIcon: string} $data
     */
    public static function fromData(string $season, array $data): self
    {
        return new self(
            $season,
            $data['name'],
            $data['colour'],
            $data['uuid'],
        );
    }
}
