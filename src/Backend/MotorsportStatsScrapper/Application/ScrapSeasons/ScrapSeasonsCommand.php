<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class ScrapSeasonsCommand implements Command
{
    private function __construct(
        private readonly string $seriesName,
    ) {
    }

    public function seriesName(): string
    {
        return $this->seriesName;
    }

    public static function fromScalar(string $seriesName): self
    {
        return new self($seriesName);
    }
}
