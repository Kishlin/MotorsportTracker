<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\CreateOrUpdateEventSessionCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventSessionRepositorySpy;

trait EventSessionServicesTrait
{
    private ?EventSessionRepositorySpy $eventSessionRepositorySpy = null;

    private ?CreateOrUpdateEventSessionCommandHandler $createEventSessionCommandHandler = null;

    public function eventSessionRepositorySpy(): EventSessionRepositorySpy
    {
        if (null === $this->eventSessionRepositorySpy) {
            $this->eventSessionRepositorySpy = new EventSessionRepositorySpy();
        }

        return $this->eventSessionRepositorySpy;
    }

    public function createEventSessionCommandHandler(): CreateOrUpdateEventSessionCommandHandler
    {
        if (null === $this->createEventSessionCommandHandler) {
            $this->createEventSessionCommandHandler = new CreateOrUpdateEventSessionCommandHandler(
                $this->eventSessionRepositorySpy(),
                $this->eventSessionRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createEventSessionCommandHandler;
    }
}
