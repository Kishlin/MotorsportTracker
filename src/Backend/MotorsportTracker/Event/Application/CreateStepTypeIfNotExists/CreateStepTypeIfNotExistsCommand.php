<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CreateStepTypeIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $label,
    ) {
    }

    public function label(): StringValueObject
    {
        return new StringValueObject($this->label);
    }

    public static function fromScalars(string $label): self
    {
        return new self($label);
    }
}
