<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

final class StandingsViewStandings extends JsonValueObject
{
    /**
     * @param array<string, array{name: string, color: string, totals: float[]}> ...$standings
     */
    public static function with(...$standings): self
    {
        return new self($standings);
    }
}
