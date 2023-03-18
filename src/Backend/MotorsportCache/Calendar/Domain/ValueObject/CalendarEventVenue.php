<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

/**
 * @property array{
 *     id: string,
 *     name: string,
 *     slug: string,
 *     country: array{
 *         id: string,
 *         code: string,
 *         name: string,
 *     }
 * } $value
 */
final class CalendarEventVenue extends JsonValueObject
{
    /**
     * @return array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     country: array{
     *         id: string,
     *         code: string,
     *         name: string,
     *     }
     * }
     */
    public function data(): array
    {
        return $this->value;
    }

    /**
     * @param array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     country: array{
     *         id: string,
     *         code: string,
     *         name: string,
     *     }
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
