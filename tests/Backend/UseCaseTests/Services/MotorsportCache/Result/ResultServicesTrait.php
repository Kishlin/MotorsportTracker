<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\ComputeEventResultsByRaceCommandHandler;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result\EventResultsBySessionsRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result\SessionClassificationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result\SessionsToComputeRepositorySpy;

trait ResultServicesTrait
{
    private ?EventResultsBySessionsRepositorySpy $eventResultsByRaceRepositorySpy = null;

    private ?SessionClassificationRepositorySpy $raceResultRepositorySpy = null;

    private ?SessionsToComputeRepositorySpy $racesToComputeRepositorySpy = null;

    private ?ComputeEventResultsByRaceCommandHandler $computeRaceResultForEventCommandHandler = null;

    private ?ViewEventResultsByRaceQueryHandler $viewEventResultsByRaceQueryHandler = null;

    public function eventResultsByRaceRepositorySpy(): EventResultsBySessionsRepositorySpy
    {
        if (null === $this->eventResultsByRaceRepositorySpy) {
            $this->eventResultsByRaceRepositorySpy = new EventResultsBySessionsRepositorySpy();
        }

        return $this->eventResultsByRaceRepositorySpy;
    }

    public function raceResultRepositorySpy(): SessionClassificationRepositorySpy
    {
        if (null === $this->raceResultRepositorySpy) {
            $this->raceResultRepositorySpy = new SessionClassificationRepositorySpy(
                $this->classificationRepositorySpy(),
                $this->countryRepositorySpy(),
                $this->driverRepositorySpy(),
                $this->entryRepositorySpy(),
                $this->teamRepositorySpy(),
            );
        }

        return $this->raceResultRepositorySpy;
    }

    public function racesToComputeRepositorySpy(): SessionsToComputeRepositorySpy
    {
        if (null === $this->racesToComputeRepositorySpy) {
            $this->racesToComputeRepositorySpy = new SessionsToComputeRepositorySpy(
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
                $this->eventResultsByRaceRepositorySpy(),
                $this->racesToComputeRepositorySpy(),
                $this->raceResultRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->computeRaceResultForEventCommandHandler;
    }

    public function viewEventResultsByRaceQueryHandler(): ViewEventResultsByRaceQueryHandler
    {
        if (null === $this->viewEventResultsByRaceQueryHandler) {
            $this->viewEventResultsByRaceQueryHandler = new ViewEventResultsByRaceQueryHandler(
                $this->eventResultsByRaceRepositorySpy(),
            );
        }

        return $this->viewEventResultsByRaceQueryHandler;
    }
}
