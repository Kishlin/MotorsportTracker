<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class SyncSeasonEventsCommand implements Command
{
    private function __construct(
        private readonly string $championship,
        private readonly int $year,
    ) {
    }

    public function championship(): StringValueObject
    {
        return new StringValueObject($this->championship);
    }

    public function year(): StrictlyPositiveIntValueObject
    {
        return new StrictlyPositiveIntValueObject($this->year);
    }

    public static function fromScalars(string $championship, int $year): self
    {
        return new self($championship, $year);
    }
}
