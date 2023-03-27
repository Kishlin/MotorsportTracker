<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\ComputeEventResultsByRaceCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result\EventResultsByRaceRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result\RaceResultRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result\RacesToComputeRepositorySpy;

trait ResultServicesTrait
{
    private ?EventResultsByRaceRepositorySpy $eventResultsByRaceRepositorySpy = null;

    private ?RaceResultRepositorySpy $raceResultRepositorySpy = null;

    private ?RacesToComputeRepositorySpy $racesToComputeRepositorySpy = null;

    private ?ComputeEventResultsByRaceCommandHandler $computeRaceResultForEventCommandHandler = null;

    public function eventResultsByRaceRepositorySpy(): EventResultsByRaceRepositorySpy
    {
        if (null === $this->eventResultsByRaceRepositorySpy) {
            $this->eventResultsByRaceRepositorySpy = new EventResultsByRaceRepositorySpy();
        }

        return $this->eventResultsByRaceRepositorySpy;
    }

    public function raceResultRepositorySpy(): RaceResultRepositorySpy
    {
        if (null === $this->raceResultRepositorySpy) {
            $this->raceResultRepositorySpy = new RaceResultRepositorySpy(
                $this->teamPresentationRepositorySpy(),
                $this->classificationRepositorySpy(),
                $this->eventSessionRepositorySpy(),
                $this->countryRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->eventRepositorySpy(),
                $this->driverRepositorySpy(),
                $this->entryRepositorySpy(),
                $this->teamRepositorySpy(),
            );
        }

        return $this->raceResultRepositorySpy;
    }

    public function racesToComputeRepositorySpy(): RacesToComputeRepositorySpy
    {
        if (null === $this->racesToComputeRepositorySpy) {
            $this->racesToComputeRepositorySpy = new RacesToComputeRepositorySpy(
                $this->sessionTypeRepositorySpy(),
                $this->eventSessionRepositorySpy(),
            );
        }

        return $this->racesToComputeRepositorySpy;
    }

    public function computeRaceResultForEventCommandHandler(): ComputeEventResultsByRaceCommandHandler
    {
        if (null === $this->computeRaceResultForEventCommandHandler) {
            $this->computeRaceResultForEventCommandHandler = new ComputeEventResultsByRaceCommandHandler(
                $this->eventResultsByRaceRepositorySpy(),
                $this->racesToComputeRepositorySpy(),
                $this->raceResultRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->computeRaceResultForEventCommandHandler;
    }
}
