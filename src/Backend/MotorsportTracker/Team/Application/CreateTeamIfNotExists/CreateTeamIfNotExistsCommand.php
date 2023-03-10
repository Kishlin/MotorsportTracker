<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateTeamIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $countryId,
        private readonly string $slug,
        private readonly string $name,
        private readonly string $code,
    ) {
    }

    public function countryId(): UuidValueObject
    {
        return new UuidValueObject($this->countryId);
    }

    public function slug(): StringValueObject
    {
        return new StringValueObject($this->slug);
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function code(): StringValueObject
    {
        return new StringValueObject($this->code);
    }

    public static function fromScalars(string $countryId, string $slug, string $name, string $code): self
    {
        return new self($countryId, $slug, $name, $code);
    }
}
