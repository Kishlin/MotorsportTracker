<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded\RefreshStandingsOnResultsRecordedHandler;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonQueryHandler;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\ViewTeamStandingsForSeasonQueryHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\RacerRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\ResultRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\DriverStandingRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\DriverStandingsForSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\EventIdForEventStepIdRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\StandingDataRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\TeamStandingRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing\TeamStandingsForSeasonRepositorySpy;

trait StandingServicesTrait
{
    private ?RefreshStandingsOnResultsRecordedHandler $refreshStandingsOnResultsRecordedHandler = null;

    private ?ViewDriverStandingsForSeasonQueryHandler $viewDriverStandingsForSeasonQueryHandler = null;

    private ?ViewTeamStandingsForSeasonQueryHandler $viewTeamStandingsForSeasonQueryHandler = null;

    private ?DriverStandingRepositorySpy $driverStandingRepositorySpy = null;

    private ?TeamStandingRepositorySpy $teamStandingRepositorySpy = null;

    private ?StandingDataRepositorySpy $standingDataRepositorySpy = null;

    private ?EventIdForEventStepIdRepositorySpy $eventIdForEventStepIdRepositorySpy = null;

    private ?DriverStandingsForSeasonRepositorySpy $driverStandingsForSeasonRepositorySpy = null;

    private ?TeamStandingsForSeasonRepositorySpy $teamStandingsForSeasonRepositorySpy = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function driverRepositorySpy(): DriverRepositorySpy;

    abstract public function eventStepRepositorySpy(): EventStepRepositorySpy;

    abstract public function resultRepositorySpy(): ResultRepositorySpy;

    abstract public function racerRepositorySpy(): RacerRepositorySpy;

    abstract public function eventRepositorySpy(): EventRepositorySpy;

    abstract public function carRepositorySpy(): CarRepositorySpy;

    public function refreshStandingsOnResultsRecordedHandler(): RefreshStandingsOnResultsRecordedHandler
    {
        if (null === $this->refreshStandingsOnResultsRecordedHandler) {
            $this->refreshStandingsOnResultsRecordedHandler = new RefreshStandingsOnResultsRecordedHandler(
                $this->eventIdForEventStepIdRepositorySpy(),
                $this->driverStandingRepositorySpy(),
                $this->teamStandingRepositorySpy(),
                $this->standingDataRepositorySpy(),
                $this->uuidGenerator(),
            );
        }

        return $this->refreshStandingsOnResultsRecordedHandler;
    }

    public function viewDriverStandingsForSeasonQueryHandler(): ViewDriverStandingsForSeasonQueryHandler
    {
        if (null === $this->viewDriverStandingsForSeasonQueryHandler) {
            $this->viewDriverStandingsForSeasonQueryHandler = new ViewDriverStandingsForSeasonQueryHandler(
                $this->driverStandingsForSeasonRepositorySpy(),
            );
        }

        return $this->viewDriverStandingsForSeasonQueryHandler;
    }

    public function viewTeamStandingsForSeasonQueryHandler(): ViewTeamStandingsForSeasonQueryHandler
    {
        if (null === $this->viewTeamStandingsForSeasonQueryHandler) {
            $this->viewTeamStandingsForSeasonQueryHandler = new ViewTeamStandingsForSeasonQueryHandler(
                $this->teamStandingsForSeasonRepositorySpy(),
            );
        }

        return $this->viewTeamStandingsForSeasonQueryHandler;
    }

    public function driverStandingRepositorySpy(): DriverStandingRepositorySpy
    {
        if (null === $this->driverStandingRepositorySpy) {
            $this->driverStandingRepositorySpy = new DriverStandingRepositorySpy();
        }

        return $this->driverStandingRepositorySpy;
    }

    public function teamStandingRepositorySpy(): TeamStandingRepositorySpy
    {
        if (null === $this->teamStandingRepositorySpy) {
            $this->teamStandingRepositorySpy = new TeamStandingRepositorySpy();
        }

        return $this->teamStandingRepositorySpy;
    }

    public function standingDataRepositorySpy(): StandingDataRepositorySpy
    {
        if (null === $this->standingDataRepositorySpy) {
            $this->standingDataRepositorySpy = new StandingDataRepositorySpy(
                $this->eventStepRepositorySpy(),
                $this->resultRepositorySpy(),
                $this->racerRepositorySpy(),
                $this->eventRepositorySpy(),
                $this->carRepositorySpy(),
            );
        }

        return $this->standingDataRepositorySpy;
    }

    public function eventIdForEventStepIdRepositorySpy(): EventIdForEventStepIdRepositorySpy
    {
        if (null === $this->eventIdForEventStepIdRepositorySpy) {
            $this->eventIdForEventStepIdRepositorySpy = new EventIdForEventStepIdRepositorySpy(
                $this->eventStepRepositorySpy(),
            );
        }

        return $this->eventIdForEventStepIdRepositorySpy;
    }

    public function driverStandingsForSeasonRepositorySpy(): DriverStandingsForSeasonRepositorySpy
    {
        if (null === $this->driverStandingsForSeasonRepositorySpy) {
            $this->driverStandingsForSeasonRepositorySpy = new DriverStandingsForSeasonRepositorySpy(
                $this->driverStandingRepositorySpy(),
                $this->eventRepositorySpy(),
            );
        }

        return $this->driverStandingsForSeasonRepositorySpy;
    }

    public function teamStandingsForSeasonRepositorySpy(): TeamStandingsForSeasonRepositorySpy
    {
        if (null === $this->teamStandingsForSeasonRepositorySpy) {
            $this->teamStandingsForSeasonRepositorySpy = new TeamStandingsForSeasonRepositorySpy(
                $this->teamStandingRepositorySpy(),
                $this->eventRepositorySpy(),
            );
        }

        return $this->teamStandingsForSeasonRepositorySpy;
    }
}
