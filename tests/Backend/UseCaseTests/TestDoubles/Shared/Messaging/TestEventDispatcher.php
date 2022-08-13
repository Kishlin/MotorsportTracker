<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Domain\DomainEvent\DriverMoveCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

final class TestEventDispatcher implements EventDispatcher
{
    public function __construct(
        private TestServiceContainer $testServiceContainer
    ) {
    }

    /**
     * @throws Exception
     */
    public function dispatch(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $event) {
            $this->handleEvent($event);
        }
    }

    /**
     * @throws Exception
     */
    private function handleEvent(DomainEvent $event): void
    {
        if ($event instanceof DriverMoveCreatedDomainEvent) {
            $this->testServiceContainer->updateRacerViewsOnDriverMoveHandler()($event);
        }
    }
}
