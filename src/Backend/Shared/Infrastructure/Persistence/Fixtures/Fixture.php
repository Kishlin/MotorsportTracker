<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use DateTimeImmutable;
use Exception;

final class Fixture
{
    /**
     * @param array<string, int|string> $data
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

    public function getString(string $key): string
    {
        return (string) $this->data[$key];
    }

    public function getInt(string $key): int
    {
        return (int) $this->data[$key];
    }

    /**
     * @throws Exception
     */
    public function getDateTime(string $key): DateTimeImmutable
    {
        return new DateTimeImmutable((string) $this->data[$key]);
    }

    /**
     * @param array<string, int|string> $data
     */
    public static function fromScalars(string $identifier, array $data): self
    {
        return new self($identifier, $data);
    }
}
