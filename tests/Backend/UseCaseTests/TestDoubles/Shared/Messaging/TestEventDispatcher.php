<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Event\Event;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

final class TestEventDispatcher implements EventDispatcher
{
    /**
     * @noinspection PhpPropertyOnlyWrittenInspection
     * @phpstan-ignore-next-line
     */
    public function __construct(private readonly TestServiceContainer $testServiceContainer)
    {
    }

    /**
     * @throws Exception
     */
    public function dispatch(Event ...$domainEvents): void
    {
        foreach ($domainEvents as $event) {
            $this->handleEvent($event);
        }
    }

    /**
     * @throws Exception
     */
    private function handleEvent(Event $event): void
    {
    }
}
