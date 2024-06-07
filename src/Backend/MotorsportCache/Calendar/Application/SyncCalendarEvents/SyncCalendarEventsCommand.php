<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final readonly class SyncCalendarEventsCommand implements Command
{
    private function __construct(
        private string $championship,
        private int $year,
    ) {}

    public function championship(): StringValueObject
    {
        return new StringValueObject($this->championship);
    }

    public function year(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->year);
    }

    public static function fromScalars(string $championship, int $year): self
    {
        return new self($championship, $year);
    }
}
