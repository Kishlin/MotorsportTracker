<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Symfony\Component\Console\Command\Command;

abstract class BackofficeKernelTestCase extends BaseKernelTestCase
{
    use KernelTestCaseTrait;

    protected function mockService(string $alias, object $mock): void
    {
        self::getContainer()->set($alias, $mock);
    }

    protected function symfonyCommand(string $alias): Command
    {
        $command = self::getContainer()->get($alias);
        assert($command instanceof Command);

        return $command;
    }
}
