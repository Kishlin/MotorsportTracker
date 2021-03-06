<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

final class TestEventDispatcher implements EventDispatcher
{
    /** @var array<string, callable[]> */
    private array $subscribers = [];

    public function __construct(TestServiceContainer $testServiceContainer)
    {
    }

    public function addSubscriber(string $event, callable $domainEventSubscriber): void
    {
        $this->subscribers[$event][] = $domainEventSubscriber;
    }

    public function dispatch(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $event) {
            foreach ($this->subscribersForEvent($event) as $subscriber) {
                $subscriber($event);
            }
        }
    }

    /**
     * @return callable[]
     */
    private function subscribersForEvent(DomainEvent $event): array
    {
        return $this->subscribers[get_class($event)] ?? [];
    }
}
