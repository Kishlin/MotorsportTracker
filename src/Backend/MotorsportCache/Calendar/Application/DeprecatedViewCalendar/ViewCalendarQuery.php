<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\DeprecatedViewCalendar;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewCalendarQuery implements Query
{
    private function __construct(
        private readonly string $start,
        private readonly string $end,
    ) {
    }

    /**
     * @throws Exception
     */
    public function start(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->start);
    }

    /**
     * @throws Exception
     */
    public function end(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->end);
    }

    public static function fromScalars(string $start, string $end): self
    {
        return new self($start, $end);
    }
}
