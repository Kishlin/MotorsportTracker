<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

abstract class JsonValueObject
{
    /**
     * @param array<int|string, mixed> $value
     */
    public function __construct(
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
}
