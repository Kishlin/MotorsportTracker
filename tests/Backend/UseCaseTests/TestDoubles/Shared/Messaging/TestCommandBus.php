<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\CreateChampionshipIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\CreateChampionshipPresentationCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\CreateSeasonIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\CreateDriverCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\CreateEventCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\CreateEventSessionCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\CreateStepTypeIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\CreateTeamCommand;
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

        if ($command instanceof CreateDriverCommand) {
            return $this->testServiceContainer->createDriverCommandHandler()($command);
        }

        if ($command instanceof CreateEventCommand) {
            return $this->testServiceContainer->createEventCommandHandler()($command);
        }

        if ($command instanceof CreateEventSessionCommand) {
            return $this->testServiceContainer->createEventSessionCommandHandler()($command);
        }

        if ($command instanceof CreateStepTypeIfNotExistsCommand) {
            return $this->testServiceContainer->createStepTypeIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateVenueIfNotExistsCommand) {
            return $this->testServiceContainer->createVenueIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateTeamPresentationCommand) {
            return $this->testServiceContainer->createTeamPresentationCommandHandler()($command);
        }

        if ($command instanceof CreateTeamCommand) {
            return $this->testServiceContainer->createTeamCommandHandler()($command);
        }

        throw new RuntimeException('Unknown command type: ' . get_class($command));
    }
}
