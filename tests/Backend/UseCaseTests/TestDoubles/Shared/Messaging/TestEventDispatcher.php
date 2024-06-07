<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Event\Event;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final readonly class TestEventDispatcher implements EventDispatcher
{
    public function __construct(
    ) {}

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
    private function handleEvent(Event $event): void {}
}
