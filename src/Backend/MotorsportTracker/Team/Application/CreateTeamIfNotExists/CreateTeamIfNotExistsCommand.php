<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateTeamIfNotExistsCommand implements Command
{
    private function __construct(
        private string $seasonId,
        private string $name,
        private ?string $color,
        private ?string $ref,
    ) {
    }

    public function seasonId(): UuidValueObject
    {
        return new UuidValueObject($this->seasonId);
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

    public static function fromScalars(
        string $seasonId,
        string $name,
        ?string $color,
        ?string $ref,
    ): self {
        return new self($seasonId, $name, $color, $ref);
    }
}
