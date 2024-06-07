<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\RaceHistory\Application\SyncTyreHistoryGraph;

use Kishlin\Backend\Shared\Domain\Bus\Task\Task;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class SyncTyreHistoryGraphTask implements Task
{
    private function __construct(
        private string $eventId,
        private string $job,
    ) {}

    public function eventId(): StringValueObject
    {
        return new StringValueObject($this->eventId);
    }

    public function job(): UuidValueObject
    {
        return new UuidValueObject($this->job);
    }

    public static function forEventAndJob(string $eventId, string $job): self
    {
        return new self($eventId, $job);
    }
}
