<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateSeasonIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $championshipId,
        private readonly int $year,
    ) {
    }

    public function championshipId(): UuidValueObject
    {
        return new UuidValueObject($this->championshipId);
    }

    public function year(): StrictlyPositiveIntValueObject
    {
        return new StrictlyPositiveIntValueObject($this->year);
    }

    public static function fromScalars(string $championshipId, int $year): self
    {
        return new self($championshipId, $year);
    }
}
