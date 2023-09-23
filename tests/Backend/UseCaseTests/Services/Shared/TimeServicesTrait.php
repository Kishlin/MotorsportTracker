<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Shared;

use Kishlin\Backend\Shared\Domain\Time\Clock;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Time\AcceptanceClock;

trait TimeServicesTrait
{
    private ?Clock $clock = null;

    public function clock(): Clock
    {
        if (null === $this->clock) {
            $this->clock = new AcceptanceClock();
        }

        return $this->clock;
    }
}
