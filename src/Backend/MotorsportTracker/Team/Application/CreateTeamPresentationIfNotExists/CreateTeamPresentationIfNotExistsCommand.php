<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateTeamPresentationIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $teamId,
        private readonly string $seasonId,
        private readonly string $countryId,
        private readonly string $name,
        private readonly ?string $color,
    ) {
    }

    public function teamId(): UuidValueObject
    {
        return new UuidValueObject($this->teamId);
    }

    public function seasonId(): UuidValueObject
    {
        return new UuidValueObject($this->seasonId);
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

    public static function fromScalars(string $teamId, string $seasonId, string $countryId, string $name, ?string $color): self
    {
        return new self($teamId, $seasonId, $countryId, $name, $color);
    }
}
