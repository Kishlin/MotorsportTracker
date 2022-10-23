<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class FixturesSaverForUseCaseTests extends FixtureSaver
{
    public function __construct(
        private TestServiceContainer $testServiceContainer
    ) {
    }

    /**
     * @throws Exception
     */
    protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        if ($aggregateRoot instanceof Country) {
            $this->testServiceContainer->countryRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Car) {
            $this->testServiceContainer->carRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof DriverMove) {
            $this->testServiceContainer->driverMoveRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Championship) {
            $this->testServiceContainer->championshipRepositorySpy()->save($aggregateRoot);

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

        if ($aggregateRoot instanceof EventStep) {
            $this->testServiceContainer->eventStepRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof StepType) {
            $this->testServiceContainer->stepTypeRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Racer) {
            $this->testServiceContainer->racerRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Team) {
            $this->testServiceContainer->teamRepositorySpy()->save($aggregateRoot);

            return;
        }

        if ($aggregateRoot instanceof Venue) {
            $this->testServiceContainer->venueRepositorySpy()->save($aggregateRoot);

            return;
        }

        throw new RuntimeException('Unable to save aggregate root of class: ' . get_class($aggregateRoot));
    }
}
