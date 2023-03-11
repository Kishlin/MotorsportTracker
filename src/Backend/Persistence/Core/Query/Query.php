<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\Query;

interface Query
{
    public function query(): string;

    /**
     * @return array<string, null|float|int|string>
     */
    public function parameters(): array;
}
