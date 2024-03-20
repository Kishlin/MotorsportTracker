<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

interface FixtureSaver
{
    /**
     * @param array<string, bool|float|int|string> $data
     */
    public function save(string $class, string $identifier, array $data): void;
}
