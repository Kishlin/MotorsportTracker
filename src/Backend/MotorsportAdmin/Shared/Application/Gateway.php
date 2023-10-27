<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Shared\Application;

interface Gateway
{
    /**
     * @param array<string, bool|float|int|string|null> $criteria
     * @return array<int|string, mixed>
     */
    public function find(string $location, array $criteria = [], ?int $limit = null): array;
}
