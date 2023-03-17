<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\CreateCountryIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CreateCountryIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $code,
        private readonly string $name,
        private readonly ?string $ref,
    ) {
    }

    public function code(): StringValueObject
    {
        return new StringValueObject($this->code);
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(string $code, string $name, ?string $ref): self
    {
        return new self($code, $name, $ref);
    }
}
