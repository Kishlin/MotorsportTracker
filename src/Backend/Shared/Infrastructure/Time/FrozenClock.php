<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Time;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Time\Clock;

/**
 * @internal used for tests controlling time
 */
final class FrozenClock implements Clock
{
    private DateTimeImmutable $frozenTime;

    public function __construct(
        ?DateTimeImmutable $time = null,
    ) {
        $this->frozenTime = $time ?? new DateTimeImmutable();
    }

    public function set(DateTimeImmutable $time): void
    {
        $this->frozenTime = $time;
    }

    public function now(): DateTimeImmutable
    {
        return $this->frozenTime;
    }
}
