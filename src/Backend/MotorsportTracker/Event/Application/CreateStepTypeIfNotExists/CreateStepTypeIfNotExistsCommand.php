<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateStepTypeIfNotExistsCommand implements Command
{
    private function __construct(
        private string $label,
    ) {
    }

    public function label(): StepTypeLabel
    {
        return new StepTypeLabel($this->label);
    }

    public static function fromScalars(string $label): self
    {
        return new self($label);
    }
}
