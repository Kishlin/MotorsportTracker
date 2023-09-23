<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Entity;

interface Mapped
{
    /**
     * @return array<string, null|bool|Entity|float|int|Mapped|string>
     */
    public function mappedData(): array;
}
