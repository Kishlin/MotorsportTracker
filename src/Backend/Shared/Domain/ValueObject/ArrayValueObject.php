<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

class ArrayValueObject
{
    /**
     * @param array<int|string, bool|float|integer|string> $value
     */
    final public function __construct(
        protected readonly array $value
    ) {
    }

    /**
     * @return array<int|string, bool|float|integer|string>
     */
    public function value(): array
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    public function asString(): string
    {
        return serialize($this->value);
    }

    public static function fromString(string $value): static
    {
        /** @var array<int|string, bool|float|integer|string> $unserialized */
        $unserialized = unserialize($value);
        assert(is_array($unserialized));

        return new static($unserialized);
    }
}
