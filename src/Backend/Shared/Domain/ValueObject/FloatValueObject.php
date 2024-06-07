<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

class FloatValueObject
{
    final public function __construct(
        protected readonly float $value
    ) {
        $this->ensureIsValid($this->value);
    }

    public static function fromOther(self $other): static
    {
        return new static($other->value);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    protected function ensureIsValid(float $value): void {}
}
