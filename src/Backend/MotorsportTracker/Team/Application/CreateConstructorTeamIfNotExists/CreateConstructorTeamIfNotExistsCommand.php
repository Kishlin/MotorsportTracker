<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateConstructorTeamIfNotExistsCommand implements Command
{
    private function __construct(
        private string $constructor,
        private string $team,
    ) {
    }

    public function constructor(): UuidValueObject
    {
        return new UuidValueObject($this->constructor);
    }

    public function team(): UuidValueObject
    {
        return new UuidValueObject($this->team);
    }

    public static function fromScalars(string $constructor, string $team): self
    {
        return new self($constructor, $team);
    }
}
