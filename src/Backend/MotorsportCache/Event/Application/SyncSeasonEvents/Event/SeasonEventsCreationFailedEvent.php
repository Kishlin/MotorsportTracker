<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Throwable;

final class SeasonEventsCreationFailedEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $championship,
        private readonly int $year,
        private readonly Throwable $throwable,
    ) {
    }

    public function championship(): string
    {
        return $this->championship;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function throwable(): Throwable
    {
        return $this->throwable;
    }

    public static function with(StringValueObject $championship, StrictlyPositiveIntValueObject $year, Throwable $throwable): self
    {
        return new self($championship->value(), $year->value(), $throwable);
    }
}
