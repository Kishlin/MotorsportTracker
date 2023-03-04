<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateDriverCommand implements Command
{
    private function __construct(
        private readonly string $name,
        private readonly string $countryId,
    ) {
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function countryId(): UuidValueObject
    {
        return new UuidValueObject($this->countryId);
    }

    public static function fromScalars(string $name, string $countryId): self
    {
        return new self($name, $countryId);
    }
}
