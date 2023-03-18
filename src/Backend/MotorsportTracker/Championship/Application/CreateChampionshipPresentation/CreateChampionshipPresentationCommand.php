<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CreateChampionshipPresentationCommand implements Command
{
    private function __construct(
        private readonly string $championship,
        private readonly string $icon,
        private readonly string $color,
    ) {
    }

    public function championship(): StringValueObject
    {
        return new StringValueObject($this->championship);
    }

    public function icon(): StringValueObject
    {
        return new StringValueObject($this->icon);
    }

    public function color(): StringValueObject
    {
        return new StringValueObject($this->color);
    }

    public static function fromScalars(string $championship, string $icon, string $color): self
    {
        return new self($championship, $icon, $color);
    }
}
