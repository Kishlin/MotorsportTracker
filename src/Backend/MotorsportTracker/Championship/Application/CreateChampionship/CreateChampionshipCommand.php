<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipSlug;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateChampionshipCommand implements Command
{
    private function __construct(
        private string $name,
        private string $slug,
    ) {
    }

    public function name(): ChampionshipName
    {
        return new ChampionshipName($this->name);
    }

    public function slug(): ChampionshipSlug
    {
        return new ChampionshipSlug($this->slug);
    }

    public static function fromScalars(string $name, string $slug): self
    {
        return new self($name, $slug);
    }
}
