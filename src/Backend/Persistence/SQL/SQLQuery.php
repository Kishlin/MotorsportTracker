<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\SQL;

use Kishlin\Backend\Persistence\Core\Query\Query;

final class SQLQuery implements Query
{
    private function __construct(
        private readonly string $query,
        private readonly array $parameters,
    ) {
    }

    public function query(): string
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<string, null|float|int|string> $parameters
     */
    public static function create(string $query, array $parameters = []): self
    {
        return new self($query, $parameters);
    }
}
