<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\CreateRetirementIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\RetirementRepositorySpy;

trait RetirementServicesTrait
{
    private ?RetirementRepositorySpy $retirementRepositorySpy = null;

    private ?CreateRetirementIfNotExistsCommandHandler $createRetirementIfNotExistsCommandHandler = null;

    public function retirementRepositorySpy(): RetirementRepositorySpy
    {
        if (null === $this->retirementRepositorySpy) {
            $this->retirementRepositorySpy = new RetirementRepositorySpy();
        }

        return $this->retirementRepositorySpy;
    }

    public function createRetirementIfNotExistsCommandHandler(): CreateRetirementIfNotExistsCommandHandler
    {
        if (null === $this->createRetirementIfNotExistsCommandHandler) {
            $this->createRetirementIfNotExistsCommandHandler = new CreateRetirementIfNotExistsCommandHandler(
                $this->retirementRepositorySpy(),
                $this->retirementRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createRetirementIfNotExistsCommandHandler;
    }
}
