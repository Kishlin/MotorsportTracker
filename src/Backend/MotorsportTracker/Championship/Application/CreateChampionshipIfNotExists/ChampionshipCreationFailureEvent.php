<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;
use Throwable;

final readonly class ChampionshipCreationFailureEvent implements Event
{
    private function __construct(
        private CreateChampionshipIfNotExistsCommand $command,
        private Throwable $throwable,
    ) {
    }

    public function command(): CreateChampionshipIfNotExistsCommand
    {
        return $this->command;
    }

    public function throwable(): Throwable
    {
        return $this->throwable;
    }

    public static function forCommand(CreateChampionshipIfNotExistsCommand $command, Throwable $t): self
    {
        return new self($command, $t);
    }
}
