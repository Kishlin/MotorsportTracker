<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Entity;

use DateTimeImmutable;

interface Mapped
{
    /**
     * @return array<string, null|bool|DateTimeImmutable|Entity|float|int|Mapped|string>
     */
    public function mappedData(): array;
}
