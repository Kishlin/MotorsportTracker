<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateEntryIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateEntryIfNotExistsCommand implements Command
{
    private function __construct(
        private string $sessionId,
        private string $driverName,
        private string $teamId,
        private int $carNumber,
    ) {
    }

    public function sessionId(): UuidValueObject
    {
        return new UuidValueObject($this->sessionId);
    }

    public function driverName(): StringValueObject
    {
        return new StringValueObject($this->driverName);
    }

    public function teamId(): UuidValueObject
    {
        return new UuidValueObject($this->teamId);
    }

    public function carNumber(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->carNumber);
    }

    public static function fromScalars(
        string $session,
        string $driver,
        string $teamId,
        int $carNumber,
    ): self {
        return new self($session, $driver, $teamId, $carNumber);
    }
}
