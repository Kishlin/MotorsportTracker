<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Domain\DomainEvent\DriverMoveCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipPresentationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventStepCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\ResultsRecordedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

final class TestEventDispatcher implements EventDispatcher
{
    public function __construct(
        private readonly TestServiceContainer $testServiceContainer,
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
        } elseif ($event instanceof ResultsRecordedDomainEvent) {
            $this->testServiceContainer->refreshStandingsOnResultsRecordedHandler()($event);
        } elseif ($event instanceof EventStepCreatedDomainEvent) {
            $this->testServiceContainer->createViewOnEventStepCreationEventHandler()($event);
        } elseif ($event instanceof ChampionshipPresentationCreatedDomainEvent) {
            $this->testServiceContainer->updateViewsAfterAChampionshipPresentationCreationHandler()($event);
        }
    }
}
