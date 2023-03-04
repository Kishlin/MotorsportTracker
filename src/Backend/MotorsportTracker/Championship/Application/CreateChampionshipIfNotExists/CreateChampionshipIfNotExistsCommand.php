<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CreateChampionshipIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $name,
        private readonly string $slug,
    ) {
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function slug(): StringValueObject
    {
        return new StringValueObject($this->slug);
    }

    public static function fromScalars(string $name, string $slug): self
    {
        return new self($name, $slug);
    }
}
