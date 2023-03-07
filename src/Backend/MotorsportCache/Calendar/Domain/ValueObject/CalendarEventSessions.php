<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

/**
 * @property array{
 *     type: string,
 *     slug: string,
 *     has_result: bool,
 *     start_date: ?int,
 *     end_date: ?int,
 * }[] $value
 */
final class CalendarEventSessions extends JsonValueObject
{
    /**
     * @return array{
     *     type: string,
     *     slug: string,
     *     has_result: bool,
     *     start_date: ?int,
     *     end_date: ?int,
     * }[]
     */
    public function data(): array
    {
        return $this->value;
    }

    /**
     * @param array{
     *     type: string,
     *     slug: string,
     *     has_result: bool,
     *     start_date: ?int,
     *     end_date: ?int,
     * }[] $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
