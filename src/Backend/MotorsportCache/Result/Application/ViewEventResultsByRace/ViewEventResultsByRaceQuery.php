<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class ViewEventResultsByRaceQuery implements Query
{
    private function __construct(
        private readonly string $event,
    ) {
    }

    public function event(): string
    {
        return $this->event;
    }

    public static function fromScalars(string $event): self
    {
        return new self($event);
    }
}
