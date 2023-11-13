<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Task;

use Kishlin\Backend\Shared\Domain\Bus\Task\Task;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Throwable;

/**
 * Adapter to the Symfony/Messenger bus used as command bus.
 * It does make sure at least one handler was invoked, but only returns the result of the last one invoked.
 * It's a global rule that any Command must invoke only one Handler, which usually lives in the same namespace.
 */
final readonly class InMemoryTaskBusUsingSymfony implements TaskBus
{
    public function __construct(
        private MessageBus $commandBus,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function execute(Task $task): bool
    {
        $stamp = $this->commandBus->dispatch($task)->last(SentStamp::class);
        assert($stamp instanceof SentStamp);

        return true;
    }
}
