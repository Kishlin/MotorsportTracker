<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateVenueIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $name,
        private readonly string $countryId,
        private readonly ?string $ref,
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

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(string $name, string $countryId, ?string $ref): self
    {
        return new self($name, $countryId, $ref);
    }
}
