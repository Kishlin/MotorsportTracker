<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Analytics;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class FixturesSaverForUseCaseTests extends FixtureSaver
{
    public function __construct(
        private readonly TestServiceContainer $testServiceContainer
    ) {
    }

    /**
     * @throws Exception
     */
    protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        // Cache

        if ($aggregateRoot instanceof CalendarEvent) {
            $this->testServiceContainer->calendarEventRepositorySpy()->save($aggregateRoot);

            return;
        }

        // Core

        if ($aggregateRoot instanceof Country) {
            $this->testServiceContainer->countryRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Championship) {
            $this->testServiceContainer->championshipRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof ChampionshipPresentation) {
            $this->testServiceContainer->championshipPresentationRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Season) {
            $this->testServiceContainer->seasonRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Driver) {
            $this->testServiceContainer->driverRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Event) {
            $this->testServiceContainer->eventRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof EventSession) {
            $this->testServiceContainer->eventSessionRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof SessionType) {
            $this->testServiceContainer->sessionTypeRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Entry) {
            $this->testServiceContainer->entryRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Classification) {
            $this->testServiceContainer->classificationRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Retirement) {
            $this->testServiceContainer->retirementRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Analytics) {
            $this->testServiceContainer->analyticsRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Team) {
            $this->testServiceContainer->teamRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof TeamPresentation) {
            $this->testServiceContainer->teamPresentationRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Venue) {
            $this->testServiceContainer->venueRepositorySpy()->save($aggregateRoot);

            return;
        }

        throw new RuntimeException('Found no repository spy to handle fixture of class: ' . get_class($aggregateRoot));
    }
}
