<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\CreateRaceLapIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\RaceLapRepositorySpy;

trait RaceLapServicesTrait
{
    private ?RaceLapRepositorySpy $raceLapRepositorySpy = null;

    private ?CreateRaceLapIfNotExistsCommandHandler $createRaceLapIfNotExistsCommandHandler = null;

    public function raceLapRepositorySpy(): RaceLapRepositorySpy
    {
        if (null === $this->raceLapRepositorySpy) {
            $this->raceLapRepositorySpy = new RaceLapRepositorySpy();
        }

        return $this->raceLapRepositorySpy;
    }

    public function createRaceLapIfNotExistsCommandHandler(): CreateRaceLapIfNotExistsCommandHandler
    {
        if (null === $this->createRaceLapIfNotExistsCommandHandler) {
            $this->createRaceLapIfNotExistsCommandHandler = new CreateRaceLapIfNotExistsCommandHandler(
                $this->raceLapRepositorySpy(),
                $this->raceLapRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createRaceLapIfNotExistsCommandHandler;
    }
}
