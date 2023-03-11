<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateDriverIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $name,
        private readonly string $shortCode,
        private readonly string $countryId,
        private readonly ?string $ref,
    ) {
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function shortCode(): StringValueObject
    {
        return new StringValueObject($this->shortCode);
    }

    public function countryId(): UuidValueObject
    {
        return new UuidValueObject($this->countryId);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(string $name, string $shortCode, string $countryId, ?string $ref): self
    {
        return new self($name, $shortCode, $countryId, $ref);
    }
}
