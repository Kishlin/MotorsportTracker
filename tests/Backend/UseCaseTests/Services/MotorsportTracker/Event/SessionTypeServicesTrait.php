<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\CreateSessionTypeIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveSessionTypeRepositorySpy;

trait SessionTypeServicesTrait
{
    private ?SaveSessionTypeRepositorySpy $sessionTypeRepositorySpy = null;

    private ?CreateSessionTypeIfNotExistsCommandHandler $createSessionTypeIfNotExistsCommandHandler = null;

    public function sessionTypeRepositorySpy(): SaveSessionTypeRepositorySpy
    {
        if (null === $this->sessionTypeRepositorySpy) {
            $this->sessionTypeRepositorySpy = new SaveSessionTypeRepositorySpy();
        }

        return $this->sessionTypeRepositorySpy;
    }

    public function createSessionTypeIfNotExistsCommandHandler(): CreateSessionTypeIfNotExistsCommandHandler
    {
        if (null === $this->createSessionTypeIfNotExistsCommandHandler) {
            $this->createSessionTypeIfNotExistsCommandHandler = new CreateSessionTypeIfNotExistsCommandHandler(
                $this->sessionTypeRepositorySpy(),
                $this->sessionTypeRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createSessionTypeIfNotExistsCommandHandler;
    }
}
