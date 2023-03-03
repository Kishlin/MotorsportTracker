<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\CreateCountryIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CreateCountryIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $code,
        private readonly string $name,
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

    public static function fromScalars(string $keyword, string $name): self
    {
        return new self($keyword, $name);
    }
}
