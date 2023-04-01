<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Event;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final class SessionNotFoundEvent implements ApplicationEvent
{
    private function __construct(
        private readonly string $championship,
        private readonly int $year,
        private readonly string $event,
        private readonly string $type,
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

    public function type(): string
    {
        return $this->type;
    }

    public static function forSession(string $championship, int $year, string $event, string $type): self
    {
        return new self($championship, $year, $event, $type);
    }
}
