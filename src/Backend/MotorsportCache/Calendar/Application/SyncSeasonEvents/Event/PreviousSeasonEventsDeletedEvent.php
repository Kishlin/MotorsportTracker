<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class PreviousSeasonEventsDeletedEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $championship,
        private readonly int $year,
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

    public static function forSeason(StringValueObject $championship, StrictlyPositiveIntValueObject $year): self
    {
        return new self($championship->value(), $year->value());
    }
}
