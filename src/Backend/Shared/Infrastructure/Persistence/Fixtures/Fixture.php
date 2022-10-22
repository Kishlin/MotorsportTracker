<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

final class Fixture
{
    /**
     * @param array<string, string> $data
     */
    private function __construct(
        private string $identifier,
        private array $data,
    ) {
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function value(string $key): string
    {
        return $this->data[$key];
    }

    /**
     * @param array<string, string> $data
     */
    public static function fromScalars(string $identifier, array $data): self
    {
        return new self($identifier, $data);
    }
}
