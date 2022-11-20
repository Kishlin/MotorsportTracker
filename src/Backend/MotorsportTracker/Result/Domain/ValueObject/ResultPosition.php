<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class ResultPosition extends StringValueObject
{
    public const DID_NOT_FINISH = 'dnf';
    public const DID_NOT_START  = 'dns';

    public function __construct(int|string $value)
    {
        $valueAsString = (string) $value;

        $this->ensureIsValid($valueAsString);

        parent::__construct($valueAsString);
    }

    private function ensureIsValid(string $value): void
    {
        if ($this->isAPositiveInteger($value)) {
            return;
        }

        if ($this->isADidNotFinish($value)) {
            return;
        }

        if ($this->isADidNotStart($value)) {
            return;
        }

        throw new InvalidValueException();
    }

    private function isAPositiveInteger(string $value): bool
    {
        return is_numeric($value) && $value >= 0;
    }

    private function isADidNotFinish(string $value): bool
    {
        return self::DID_NOT_FINISH === $value;
    }

    private function isADidNotStart(string $value): bool
    {
        return self::DID_NOT_START === $value;
    }
}
