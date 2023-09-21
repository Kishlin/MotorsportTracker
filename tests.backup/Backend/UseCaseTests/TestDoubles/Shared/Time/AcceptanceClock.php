<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Time;

use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Time\Clock;

final class AcceptanceClock implements Clock
{
    private readonly DateTimeImmutable $now;

    public function __construct()
    {
        $this->now = new DateTimeImmutable();
    }

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }
}
