<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Shared\Application;

interface Gateway
{
    /**
     * @param array<array<string, null|bool|float|int|string>> $criteria
     * @param array<string, string>                            $sorts
     *
     * @return array<int|string, array<int|string, mixed>>
     */
    public function find(string $location, array $criteria = [], array $sorts = [], ?int $limit = null): array;
}
