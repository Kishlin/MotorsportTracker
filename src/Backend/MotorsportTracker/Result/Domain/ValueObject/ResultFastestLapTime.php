<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class ResultFastestLapTime extends StringValueObject
{
    private const LAP_TIME_PATTERN = "/^[\\d]{1,2}'[\\d]{2}\\.[\\d]{3}$/";

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);

        parent::__construct($value);
    }

    protected function ensureIsValid(string $value): void
    {
        $this->ensureIsPositive($value);
    }

    private function ensureIsPositive(string $value): void
    {
        if (1 !== preg_match(self::LAP_TIME_PATTERN, $value)) {
            throw new InvalidValueException("{$value} does not respect the lap time pattern.");
        }
    }
}
