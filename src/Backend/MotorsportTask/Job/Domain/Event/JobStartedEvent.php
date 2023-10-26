<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Domain\Event;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final class JobStartedEvent implements Event
{
    public const JOB_NOT_FOUND       = 0;
    public const JOB_ALREADY_STARTED = 1;

    private function __construct(
        private readonly string $uuid,
        private null|int $error,
    ) {
    }

    public function flagJobAsNotFound(): void
    {
        $this->error = self::JOB_NOT_FOUND;
    }

    public function flagJobAsAlreadyStarted(): void
    {
        $this->error = self::JOB_ALREADY_STARTED;
    }

    public function isClearToContinue(): bool
    {
        return null === $this->error;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public static function forJob(string $uuid): self
    {
        return new self($uuid, null);
    }
}
