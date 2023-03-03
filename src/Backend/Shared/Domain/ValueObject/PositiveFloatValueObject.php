<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;

class PositiveFloatValueObject extends FloatValueObject
{
    protected function ensureIsValid(float $value): void
    {
        $this->ensureIsPositive($value);
    }

    private function ensureIsPositive(float $value): void
    {
        if (0 > $value) {
            throw new InvalidValueException("Given value {$value} is not positive.");
        }
    }
}
