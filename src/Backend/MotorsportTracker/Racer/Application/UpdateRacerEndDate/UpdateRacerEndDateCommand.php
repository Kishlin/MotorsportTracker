<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class UpdateRacerEndDateCommand implements Command
{
    private function __construct(
        private string $driverId,
        private string $championship,
        private string $newEndDate,
    ) {
    }

    public function driverId(): string
    {
        return $this->driverId;
    }

    public function newEndDate(): string
    {
        return $this->newEndDate;
    }

    public function championship(): string
    {
        return $this->championship;
    }

    public static function fromScalars(string $driverId, string $championship, string $newEndDate): self
    {
        return new self($driverId, $championship, $newEndDate);
    }
}
