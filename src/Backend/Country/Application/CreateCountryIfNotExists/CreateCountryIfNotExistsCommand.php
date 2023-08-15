<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\CreateCountryIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final readonly class CreateCountryIfNotExistsCommand implements Command
{
    private function __construct(
        private ?string $code,
        private string $name,
        private ?string $ref,
    ) {
    }

    public function code(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->code);
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function ref(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->ref);
    }

    public static function fromScalars(?string $code, string $name, ?string $ref): self
    {
        return new self($code, $name, $ref);
    }
}
