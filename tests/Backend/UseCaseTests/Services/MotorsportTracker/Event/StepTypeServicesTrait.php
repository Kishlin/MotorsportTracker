<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\CreateStepTypeIfNotExistsCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\StepTypeRepositorySpy;

trait StepTypeServicesTrait
{
    private ?StepTypeRepositorySpy $stepTypeRepositorySpy = null;

    private ?CreateStepTypeIfNotExistsCommandHandler $createStepTypeIfNotExistsCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    public function stepTypeRepositorySpy(): StepTypeRepositorySpy
    {
        if (null === $this->stepTypeRepositorySpy) {
            $this->stepTypeRepositorySpy = new StepTypeRepositorySpy();
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
