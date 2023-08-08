<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class NoSessionsFoundEvent implements ApplicationEvent
{
    private function __construct(
        private string $championship,
        private int $year,
        private string $event,
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

    public function event(): string
    {
        return $this->event;
    }

    public static function forCommand(ScrapClassificationCommand $command): self
    {
        return self::fromScalars($command->championship(), $command->year(), $command->event());
    }

    public static function fromScalars(string $championship, int $year, string $event): self
    {
        return new self($championship, $year, $event);
    }
}
