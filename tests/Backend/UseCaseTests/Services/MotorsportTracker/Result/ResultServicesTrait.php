<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\RecordResultsCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\ResultRepositorySpy;

trait ResultServicesTrait
{
    private ?ResultRepositorySpy $resultRepositorySpy = null;

    private ?RecordResultsCommandHandler $recordResultsCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    public function resultRepositorySpy(): ResultRepositorySpy
    {
        if (null === $this->resultRepositorySpy) {
            $this->resultRepositorySpy = new ResultRepositorySpy();
        }

        return $this->resultRepositorySpy;
    }

    public function recordResultsCommandHandler(): RecordResultsCommandHandler
    {
        if (null === $this->recordResultsCommandHandler) {
            $this->recordResultsCommandHandler = new RecordResultsCommandHandler(
                $this->eventDispatcher(),
                $this->uuidGenerator(),
                $this->resultRepositorySpy(),
            );
        }

        return $this->recordResultsCommandHandler;
    }
}
