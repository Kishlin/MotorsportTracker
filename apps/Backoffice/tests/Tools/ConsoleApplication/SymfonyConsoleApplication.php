<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools\ConsoleApplication;

use DateInterval;
use Kishlin\Backend\Shared\Domain\Time\Clock;
use Kishlin\Backend\Shared\Infrastructure\Time\FrozenClock;
use Symfony\Bundle\FrameworkBundle\Console\Application;

final class SymfonyConsoleApplication extends Application implements ConsoleApplicationInterface
{
    public function advanceInTime(DateInterval $dateInterval): void
    {
        $clock = $this->getKernel()->getContainer()->get(Clock::class);

        assert($clock instanceof FrozenClock); // @phpstan-ignore-line

        $clock->set($clock->now()->add($dateInterval));
    }
}
