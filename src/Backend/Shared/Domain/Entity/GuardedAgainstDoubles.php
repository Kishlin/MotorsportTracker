<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Entity;

interface GuardedAgainstDoubles
{
    public function strategyOnDuplicate(): DuplicateStrategy;

    /**
     * @return array<int, string[]>
     */
    public function uniquenessConstraints(): array;
}
