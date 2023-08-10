<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;

final readonly class EntryNotFoundEvent implements ApplicationEvent
{
    private function __construct(
        private string $session,
        private string $carNumber,
    ) {
    }

    public function session(): string
    {
        return $this->session;
    }

    public function carNumber(): string
    {
        return $this->carNumber;
    }

    public static function fromScalars(string $session, string $carNumber): self
    {
        return new self($session, $carNumber);
    }
}
