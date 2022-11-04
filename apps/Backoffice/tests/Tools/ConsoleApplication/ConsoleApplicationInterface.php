<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools\ConsoleApplication;

use Symfony\Component\Console\Command\Command;

interface ConsoleApplicationInterface
{
    public function find(string $name): Command;
}
