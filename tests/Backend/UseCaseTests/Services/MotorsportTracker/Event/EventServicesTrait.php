<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\CreateEventCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;

trait EventServicesTrait
{
    private ?EventRepositorySpy $eventRepositorySpy = null;

    private ?CreateEventCommandHandler $createEventCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    public function eventRepositorySpy(): EventRepositorySpy
    {
        if (null === $this->eventRepositorySpy) {
            $this->eventRepositorySpy = new EventRepositorySpy();
        }

        return $this->eventRepositorySpy;
    }

    public function createEventCommandHandler(): CreateEventCommandHandler
    {
        if (null === $this->createEventCommandHandler) {
            $this->createEventCommandHandler = new CreateEventCommandHandler(
                $this->eventRepositorySpy(),
                $this->eventRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createEventCommandHandler;
    }
}
