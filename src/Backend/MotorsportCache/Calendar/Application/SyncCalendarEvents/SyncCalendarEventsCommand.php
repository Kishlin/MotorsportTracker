<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class SyncCalendarEventsCommand implements Command
{
    private function __construct(
        private readonly string $seasonSlug,
        private readonly int $year,
    ) {
    }

    public function seasonSlug(): StringValueObject
    {
        return new StringValueObject($this->seasonSlug);
    }

    public function year(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->year);
    }

    public static function fromScalars(string $seasonSlug, int $year): self
    {
        return new self($seasonSlug, $year);
    }
}
