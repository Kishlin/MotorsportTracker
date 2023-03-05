<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\CreateEventStepCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveEventStepRepositorySpy;

trait EventStepServicesTrait
{
    private ?SaveEventStepRepositorySpy $eventStepRepositorySpy = null;

    private ?CreateEventStepCommandHandler $createEventStepCommandHandler = null;

    public function eventStepRepositorySpy(): SaveEventStepRepositorySpy
    {
        if (null === $this->eventStepRepositorySpy) {
            $this->eventStepRepositorySpy = new SaveEventStepRepositorySpy();
        }

        return $this->eventStepRepositorySpy;
    }

    public function createEventStepCommandHandler(): CreateEventStepCommandHandler
    {
        if (null === $this->createEventStepCommandHandler) {
            $this->createEventStepCommandHandler = new CreateEventStepCommandHandler(
                $this->eventStepRepositorySpy(),
                $this->eventStepRepositorySpy(),
                $this->eventStepRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createEventStepCommandHandler;
    }
}
