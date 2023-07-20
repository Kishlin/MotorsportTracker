<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final readonly class CreateConstructorIfNotExistsCommand implements Command
{
    private function __construct(
        private string $name,
        private ?string $ref,
    ) {
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(string $name, ?string $ref): self
    {
        return new self($name, $ref);
    }
}
