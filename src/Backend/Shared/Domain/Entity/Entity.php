<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Entity;

abstract class Entity
{
    /**
     * @return array<string, null|bool|float|int|string>
     */
    abstract public function mappedData(): array;

    /**
     * @return array<string, null|bool|float|int|string>
     */
    abstract public function mappedUniqueness(): array;
}
