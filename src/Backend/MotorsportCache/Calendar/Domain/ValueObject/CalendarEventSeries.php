<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\ArrayValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;

/**
 * @property array{
 *     name: string,
 *     slug: string,
 *     year: int,
 *     icon: string,
 *     color: string,
 * } $value
 */
final class CalendarEventSeries extends ArrayValueObject
{
    /**
     * @return array{
     *     name: string,
     *     slug: string,
     *     year: int,
     *     icon: string,
     *     color: string,
     * }
     */
    public function data(): array
    {
        return $this->value;
    }

    /**
     * @param array{
     *     name: string,
     *     year: int,
     *     icon: string,
     *     color: string,
     * } $data
     */
    public static function fromData(array $data): self
    {
        $data['slug'] = StringHelper::slugify($data['name']);

        return new self($data);
    }
}
