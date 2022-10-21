<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\CreateEventStepCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;

trait EventStepServicesTrait
{
    private ?EventStepRepositorySpy $eventStepRepositorySpy = null;

    private ?CreateEventStepCommandHandler $createEventStepCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    public function eventStepRepositorySpy(): EventStepRepositorySpy
    {
        if (null === $this->eventStepRepositorySpy) {
            $this->eventStepRepositorySpy = new EventStepRepositorySpy();
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
