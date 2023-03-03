<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\ConsoleApplication;

use DateInterval;
use Symfony\Component\Console\Command\Command;

interface ConsoleApplicationInterface
{
    public function find(string $name): Command;

    public function advanceInTime(DateInterval $dateInterval): void;
}
