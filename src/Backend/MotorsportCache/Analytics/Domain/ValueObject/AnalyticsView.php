<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

final class AnalyticsView extends JsonValueObject
{
    /**
     * @param array<int, array<string, null|array<string, mixed>|float|int|string>> $analytics
     */
    public static function with(array $analytics): self
    {
        return new self($analytics);
    }
}
