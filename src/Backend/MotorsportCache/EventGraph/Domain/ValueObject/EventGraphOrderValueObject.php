<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphOrder;

final class EventGraphOrderValueObject
{
    public function __construct(
        protected readonly EventGraphOrder $value
    ) {}

    public function value(): EventGraphOrder
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value() === $this->value;
    }

    public function asInt(): int
    {
        return $this->value->value;
    }
}
