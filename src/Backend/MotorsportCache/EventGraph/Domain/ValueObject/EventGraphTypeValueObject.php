<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphType;

final class EventGraphTypeValueObject
{
    public function __construct(
        protected readonly EventGraphType $value
    ) {}

    public function value(): EventGraphType
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    public function asString(): string
    {
        return $this->value->value;
    }
}
