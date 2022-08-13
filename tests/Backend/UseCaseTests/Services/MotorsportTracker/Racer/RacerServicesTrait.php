<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Racer;

use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\DriverMoveDataGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\UpdateRacerViewsOnDriverMoveHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\RacerRepositorySpy;

trait RacerServicesTrait
{
    private ?RacerRepositorySpy $racerRepositorySpy = null;

    private ?UpdateRacerViewsOnDriverMoveHandler $updateRacerViewsOnDriverMoveHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function driverMoveRepositorySpy(): DriverMoveDataGateway;

    public function racerRepositorySpy(): RacerRepositorySpy
    {
        if (null === $this->racerRepositorySpy) {
            $this->racerRepositorySpy = new RacerRepositorySpy($this->driverMoveRepositorySpy());
        }

        return $this->racerRepositorySpy;
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
}
