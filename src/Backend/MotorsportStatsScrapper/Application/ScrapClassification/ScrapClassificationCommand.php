<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class ScrapClassificationCommand implements Command
{
    private function __construct(
        private string $championship,
        private int $year,
        private ?string $event,
    ) {
    }

    public function championship(): string
    {
        return $this->championship;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function event(): ?string
    {
        return $this->event;
    }

    public static function fromScalars(string $championship, int $year, ?string $event = null): self
    {
        return new self($championship, $year, $event);
    }
}
