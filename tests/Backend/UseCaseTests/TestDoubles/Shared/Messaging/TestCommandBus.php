<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\RecordDriverMoveCommand;
use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\RegisterCarCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommand;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\CreateDriverCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\CreateStepTypeIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\CreateTeamCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestCommandBus implements CommandBus
{
    public function __construct(
        private TestServiceContainer $testServiceContainer
    ) {
    }

    public function execute(Command $command): mixed
    {
        if ($command instanceof CreateCountryIfNotExistsCommand) {
            return $this->testServiceContainer->createCountryIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateChampionshipCommand) {
            return $this->testServiceContainer->createChampionshipCommandHandler()($command);
        }

        if ($command instanceof CreateSeasonCommand) {
            return $this->testServiceContainer->createSeasonCommandHandler()($command);
        }

        if ($command instanceof CreateDriverCommand) {
            return $this->testServiceContainer->createDriverCommandHandler()($command);
        }

        if ($command instanceof CreateStepTypeIfNotExistsCommand) {
            return $this->testServiceContainer->createStepTypeIfNotExistsCommandHandler()($command);
        }

        if ($command instanceof CreateVenueCommand) {
            return $this->testServiceContainer->createVenueCommandHandler()($command);
        }

        if ($command instanceof CreateTeamCommand) {
            return $this->testServiceContainer->createTeamCommandHandler()($command);
        }

        if ($command instanceof RecordDriverMoveCommand) {
            return $this->testServiceContainer->recordDriverMoveCommandHandler()($command);
        }

        if ($command instanceof RegisterCarCommand) {
            return $this->testServiceContainer->registerCarCommandHandler()($command);
        }

        throw new RuntimeException('Unknown command type: ' . get_class($command));
    }
}
