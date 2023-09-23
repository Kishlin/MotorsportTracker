<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application\Loader;

interface EntityGateway
{
    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    public function save(string $location, array $data): string;

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    public function update(string $location, string $id, array $data): void;
}
