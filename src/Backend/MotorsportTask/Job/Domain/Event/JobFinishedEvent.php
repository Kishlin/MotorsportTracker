<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Domain\Event;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final readonly class JobFinishedEvent implements Event
{
    private function __construct(
        private string $uuid,
    ) {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public static function forJob(string $uuid): self
    {
        return new self($uuid);
    }
}
