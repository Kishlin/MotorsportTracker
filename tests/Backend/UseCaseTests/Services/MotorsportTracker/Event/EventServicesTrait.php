<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\CreateEventIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveEventRepositorySpy;

trait EventServicesTrait
{
    private ?SaveEventRepositorySpy $eventRepositorySpy = null;

    private ?CreateEventIfNotExistsCommandHandler $createEventCommandHandler = null;

    public function eventRepositorySpy(): SaveEventRepositorySpy
    {
        if (null === $this->eventRepositorySpy) {
            $this->eventRepositorySpy = new SaveEventRepositorySpy();
        }

        return $this->eventRepositorySpy;
    }

    public function createEventCommandHandler(): CreateEventIfNotExistsCommandHandler
    {
        if (null === $this->createEventCommandHandler) {
            $this->createEventCommandHandler = new CreateEventIfNotExistsCommandHandler(
                $this->eventRepositorySpy(),
                $this->eventRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createEventCommandHandler;
    }
}
