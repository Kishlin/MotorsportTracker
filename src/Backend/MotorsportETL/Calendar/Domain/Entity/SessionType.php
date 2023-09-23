<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class SessionType extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly string $label,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'label' => $this->label,
        ];
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['label'],
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public static function forLabel(string $label): self
    {
        return new self($label);
    }
}
