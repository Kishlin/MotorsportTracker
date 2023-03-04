<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class SearchDriverQuery implements Query
{
    private function __construct(
        private readonly string $name,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function fromScalars(string $name): self
    {
        return new self($name);
    }
}
