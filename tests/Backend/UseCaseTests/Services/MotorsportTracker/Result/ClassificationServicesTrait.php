<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\CreateClassificationIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\ClassificationRepositorySpy;

trait ClassificationServicesTrait
{
    private ?ClassificationRepositorySpy $classificationRepositorySpy = null;

    private ?CreateClassificationIfNotExistsCommandHandler $createClassificationIfNotExistsCommandHandler = null;

    public function classificationRepositorySpy(): ClassificationRepositorySpy
    {
        if (null === $this->classificationRepositorySpy) {
            $this->classificationRepositorySpy = new ClassificationRepositorySpy();
        }

        return $this->classificationRepositorySpy;
    }

    public function createClassificationIfNotExistsCommandHandler(): CreateClassificationIfNotExistsCommandHandler
    {
        if (null === $this->createClassificationIfNotExistsCommandHandler) {
            $this->createClassificationIfNotExistsCommandHandler = new CreateClassificationIfNotExistsCommandHandler(
                $this->classificationRepositorySpy(),
                $this->classificationRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createClassificationIfNotExistsCommandHandler;
    }
}
