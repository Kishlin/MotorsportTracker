<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Racer;

use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\GetAllRacersForDateTimeQueryHandler;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate\UpdateRacerEndDateCommandHandler;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\DriverMoveDataGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\UpdateRacerViewsOnDriverMoveHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\FindRacerRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\RacerRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\RacerViewRepositorySpy;

trait RacerServicesTrait
{
    private ?RacerRepositorySpy $racerRepositorySpy = null;

    private ?RacerViewRepositorySpy $racerViewRepositorySpy = null;

    private ?FindRacerRepositorySpy $findRacerRepositorySpy = null;

    private ?UpdateRacerViewsOnDriverMoveHandler $updateRacerViewsOnDriverMoveHandler = null;

    private ?GetAllRacersForDateTimeQueryHandler $getAllRacersForDateTimeQueryHandler = null;

    private ?UpdateRacerEndDateCommandHandler $updateRacerEndDateCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function carRepositorySpy(): CarRepositorySpy;

    abstract public function seasonRepositorySpy(): SeasonRepositorySpy;

    abstract public function driverRepositorySpy(): DriverRepositorySpy;

    abstract public function championshipRepositorySpy(): ChampionshipRepositorySpy;

    abstract public function driverMoveRepositorySpy(): DriverMoveDataGateway;

    public function racerRepositorySpy(): RacerRepositorySpy
    {
        if (null === $this->racerRepositorySpy) {
            $this->racerRepositorySpy = new RacerRepositorySpy($this->driverMoveRepositorySpy());
        }

        return $this->racerRepositorySpy;
    }

    public function racerViewRepositorySpy(): RacerViewRepositorySpy
    {
        if (null === $this->racerViewRepositorySpy) {
            $this->racerViewRepositorySpy = new RacerViewRepositorySpy(
                $this->carRepositorySpy(),
                $this->racerRepositorySpy(),
                $this->driverRepositorySpy(),
            );
        }

        return $this->racerViewRepositorySpy;
    }

    public function findRacerRepositorySpy(): FindRacerRepositorySpy
    {
        if (null === $this->findRacerRepositorySpy) {
            $this->findRacerRepositorySpy = new FindRacerRepositorySpy(
                $this->championshipRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->racerRepositorySpy(),
                $this->carRepositorySpy(),
            );
        }

        return $this->findRacerRepositorySpy;
    }

    public function updateRacerViewsOnDriverMoveHandler(): UpdateRacerViewsOnDriverMoveHandler
    {
        if (null === $this->updateRacerViewsOnDriverMoveHandler) {
            $this->updateRacerViewsOnDriverMoveHandler = new UpdateRacerViewsOnDriverMoveHandler(
                $this->driverMoveRepositorySpy(),
                $this->racerRepositorySpy(),
                $this->racerRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->updateRacerViewsOnDriverMoveHandler;
    }

    public function getAllRacersForDateTimeQueryHandler(): GetAllRacersForDateTimeQueryHandler
    {
        if (null === $this->getAllRacersForDateTimeQueryHandler) {
            $this->getAllRacersForDateTimeQueryHandler = new GetAllRacersForDateTimeQueryHandler(
                $this->racerViewRepositorySpy(),
            );
        }

        return $this->getAllRacersForDateTimeQueryHandler;
    }

    public function updateRacerEndDateCommandHandler(): UpdateRacerEndDateCommandHandler
    {
        if (null === $this->updateRacerEndDateCommandHandler) {
            $this->updateRacerEndDateCommandHandler = new UpdateRacerEndDateCommandHandler(
                $this->findRacerRepositorySpy(),
                $this->racerRepositorySpy(),
            );
        }

        return $this->updateRacerEndDateCommandHandler;
    }
}
