<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateTeamIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $countryId,
        private readonly string $name,
        private readonly ?string $color,
        private readonly ?string $ref,
    ) {
    }

    public function countryId(): UuidValueObject
    {
        return new UuidValueObject($this->countryId);
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function color(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->color);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(string $countryId, string $name, ?string $color, ?string $ref): self
    {
        return new self($countryId, $name, $color, $ref);
    }
}
