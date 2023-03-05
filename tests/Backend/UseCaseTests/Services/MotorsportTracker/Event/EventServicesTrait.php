<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\CreateEventCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveEventRepositorySpy;

trait EventServicesTrait
{
    private ?SaveEventRepositorySpy $eventRepositorySpy = null;

    private ?CreateEventCommandHandler $createEventCommandHandler = null;

    public function eventRepositorySpy(): SaveEventRepositorySpy
    {
        if (null === $this->eventRepositorySpy) {
            $this->eventRepositorySpy = new SaveEventRepositorySpy();
        }

        return $this->eventRepositorySpy;
    }

    public function createEventCommandHandler(): CreateEventCommandHandler
    {
        if (null === $this->createEventCommandHandler) {
            $this->createEventCommandHandler = new CreateEventCommandHandler(
                $this->eventRepositorySpy(),
                $this->uuidGenerator(),
            );
        }

        return $this->createEventCommandHandler;
    }
}
