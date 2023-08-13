<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

final class HistoriesValueObject extends JsonValueObject
{
    /**
     * @param array{
     *     session: array{id: string, type: string},
     *     laps: int,
     *     series: array<array{
     *         color: string,
     *         index: int,
     *         car_number: string,
     *         short_code: string,
     *         positions: array<int, int>,
     *         pits: array<int, bool>,
     *     }>
     * } $histories
     */
    public static function with(array $histories): self
    {
        return new self($histories);
    }
}
