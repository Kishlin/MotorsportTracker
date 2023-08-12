<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RegisterAdditionalDriver;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class RegisterAdditionalDriverCommand implements Command
{
    private function __construct(
        private string $entry,
        private string $driver,
    ) {
    }

    public function entry(): UuidValueObject
    {
        return new UuidValueObject($this->entry);
    }

    public function driver(): UuidValueObject
    {
        return new UuidValueObject($this->driver);
    }

    public static function fromScalars(string $entry, string $driver): self
    {
        return new self($entry, $driver);
    }
}
