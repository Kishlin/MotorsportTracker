<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestCommandBus implements CommandBus
{
    public function __construct(
        private TestServiceContainer $testServiceContainer
    ) {
    }

    public function execute(Command $command): mixed
    {
        if ($command instanceof CreateChampionshipCommand) {
            return $this->testServiceContainer->createChampionshipCommandHandler()($command);
        }

        throw new RuntimeException('Unknown command type: ' . get_class($command));
    }
}
