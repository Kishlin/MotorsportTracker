<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\SQL;

use Kishlin\Backend\Persistence\Core\Query\Query;

final class SQLQuery implements Query
{
    /**
     * @param array<string, null|bool|float|int|string> $parameters
     */
    private function __construct(
        private readonly string $query,
        private readonly array $parameters,
    ) {}

    public function query(): string
    {
        return $this->query;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<string, null|bool|float|int|string> $parameters
     */
    public static function create(string $query, array $parameters = []): self
    {
        return new self($query, $parameters);
    }
}
