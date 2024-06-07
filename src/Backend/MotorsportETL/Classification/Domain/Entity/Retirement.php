<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Retirement extends Entity implements GuardedAgainstDoubles
{
    public function __construct(
        private readonly Entry $entry,
        private readonly string $reason,
        private readonly string $type,
        private readonly bool $dns,
        private readonly ?int $lap,
    ) {}

    public function mappedData(): array
    {
        return [
            'entry'  => $this->entry,
            'reason' => $this->reason,
            'type'   => $this->type,
            'dns'    => $this->dns ? 1 : 0,
            'lap'    => $this->lap,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::UPDATE;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['entry'],
        ];
    }

    /**
     * @param array{
     *     reason: string,
     *     type: string,
     *     dns: bool,
     *     lap: int,
     *     details: null,
     * } $retirement
     */
    public static function fromData(Entry $entry, array $retirement): self
    {
        return new self(
            $entry,
            $retirement['reason'],
            $retirement['type'],
            $retirement['dns'],
            $retirement['lap'],
        );
    }
}
