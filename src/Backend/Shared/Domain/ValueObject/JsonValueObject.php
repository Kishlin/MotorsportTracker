<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use JsonException;

class JsonValueObject
{
    /**
     * @param array<int|string, mixed> $value
     */
    final public function __construct(
        protected readonly array $value
    ) {
    }

    /**
     * @return array<int|string, mixed>
     */
    public function value(): array
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    /**
     * @throws JsonException
     */
    public function asString(): string
    {
        return json_encode($this->value, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * @throws JsonException
     */
    public static function fromString(string $value): static
    {
        $decoded = self::decode($value);

        return new static($decoded);
    }

    /**
     * @throws JsonException
     *
     * @return array<int|string, mixed>
     */
    protected static function decode(string $value): array
    {
        /** @var array<int|string, mixed> $decoded */
        $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        assert(is_array($decoded));

        return $decoded;
    }
}
