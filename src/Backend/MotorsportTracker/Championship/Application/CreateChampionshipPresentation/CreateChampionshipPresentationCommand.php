<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CreateChampionshipPresentationCommand implements Command
{
    private function __construct(
        private readonly string $championshipSlug,
        private readonly string $icon,
        private readonly string $color,
    ) {
    }

    public function championshipSlug(): StringValueObject
    {
        return new StringValueObject($this->championshipSlug);
    }

    public function icon(): StringValueObject
    {
        return new StringValueObject($this->icon);
    }

    public function color(): StringValueObject
    {
        return new StringValueObject($this->color);
    }

    public static function fromScalars(string $championshipSlug, string $icon, string $color): self
    {
        return new self($championshipSlug, $icon, $color);
    }
}
