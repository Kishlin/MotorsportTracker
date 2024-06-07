<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent;

use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Throwable;

final class FailedToSaveEventGraphEvent implements ApplicationEvent
{
    private function __construct(
        private readonly Throwable $e,
    ) {}

    public function throwable(): Throwable
    {
        return $this->e;
    }

    public static function forThrowable(Throwable $e): self
    {
        return new self($e);
    }
}
