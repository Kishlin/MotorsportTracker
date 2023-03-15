<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;

final class CreateTeamIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly ?string $ref,
    ) {
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(?string $ref): self
    {
        return new self($ref);
    }
}
