<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

class ArrayValueObject
{
    /**
     * @param array<int|string, float|integer|string> $value
     */
    public function __construct(
        protected readonly array $value
    ) {
    }

    /**
     * @return array<int|string, float|integer|string>
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
