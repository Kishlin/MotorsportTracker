<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\SyncCalendarEventsCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\CreateChampionshipIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\CreateChampionshipPresentationCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\CreateSeasonIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\CreateDriverIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\CreateEventIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\CreateEventSessionIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\CreateSessionTypeIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\CreateTeamIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\CreateTeamPresentationCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\CreateVenueIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestCommandBus implements CommandBus
{
    public function __construct(
        private readonly TestServiceContainer $testServiceContainer
    ) {
    }

    /**
     * @throws Exception
     */
    public function execute(Command $command): mixed
    {
        // Cache

        if ($command instanceof SyncCalendarEventsCommand) {
            $this->testServiceContainer->syncCalendarEventsCommandHandler()($command);

            return null;
        }

        // Core

        if ($command instanceof CreateCountryIfNotExistsCommand) {
            return $this->testServiceContainer->createCountryIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateChampionshipIfNotExistsCommand) {
            return $this->testServiceContainer->createChampionshipCommandHandler()($command);
        }

        if ($command instanceof CreateChampionshipPresentationCommand) {
            return $this->testServiceContainer->createChampionshipPresentationCommandHandler()($command);
        }

        if ($command instanceof CreateSeasonIfNotExistsCommand) {
            return $this->testServiceContainer->createSeasonCommandHandler()($command);
        }

        if ($command instanceof CreateDriverIfNotExistsCommand) {
            return $this->testServiceContainer->createDriverIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateEventIfNotExistsCommand) {
            return $this->testServiceContainer->createEventCommandHandler()($command);
        }

        if ($command instanceof CreateEventSessionIfNotExistsCommand) {
            return $this->testServiceContainer->createEventSessionCommandHandler()($command);
        }

        if ($command instanceof CreateSessionTypeIfNotExistsCommand) {
            return $this->testServiceContainer->createSessionTypeIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateVenueIfNotExistsCommand) {
            return $this->testServiceContainer->createVenueIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateTeamPresentationCommand) {
            return $this->testServiceContainer->createTeamPresentationCommandHandler()($command);
        }

        if ($command instanceof CreateTeamIfNotExistsCommand) {
            return $this->testServiceContainer->createTeamIfNotExistsCommandHandler()($command);
        }

        throw new RuntimeException('Unknown command type: ' . get_class($command));
    }
}
