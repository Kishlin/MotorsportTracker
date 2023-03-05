<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\CreateStepTypeIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveStepTypeRepositorySpy;

trait StepTypeServicesTrait
{
    private ?SaveStepTypeRepositorySpy $stepTypeRepositorySpy = null;

    private ?CreateStepTypeIfNotExistsCommandHandler $createStepTypeIfNotExistsCommandHandler = null;

    public function stepTypeRepositorySpy(): SaveStepTypeRepositorySpy
    {
        if (null === $this->stepTypeRepositorySpy) {
            $this->stepTypeRepositorySpy = new SaveStepTypeRepositorySpy();
        }

        return $this->stepTypeRepositorySpy;
    }

    public function createStepTypeIfNotExistsCommandHandler(): CreateStepTypeIfNotExistsCommandHandler
    {
        if (null === $this->createStepTypeIfNotExistsCommandHandler) {
            $this->createStepTypeIfNotExistsCommandHandler = new CreateStepTypeIfNotExistsCommandHandler(
                $this->stepTypeRepositorySpy(),
                $this->stepTypeRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createStepTypeIfNotExistsCommandHandler;
    }
}
