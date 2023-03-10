<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CreateChampionshipIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $name,
        private readonly ?string $shortName,
        private readonly string $shortCode,
        private readonly ?string $ref,
    ) {
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function shortName(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->shortName);
    }

    public function shortCode(): StringValueObject
    {
        return new StringValueObject($this->shortCode);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(string $name, ?string $shortName, string $shortCode, ?string $ref): self
    {
        return new self($name, $shortName, $shortCode, $ref);
    }
}
