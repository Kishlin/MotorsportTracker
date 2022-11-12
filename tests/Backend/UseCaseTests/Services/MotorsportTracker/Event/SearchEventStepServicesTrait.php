<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SearchEventStepIdAndDateTimeRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\StepTypeRepositorySpy;

trait SearchEventStepServicesTrait
{
    private ?SearchEventStepIdAndDateTimeQueryHandler $searchEventStepIdAndDateTimeQueryHandler = null;

    private ?SearchEventStepIdAndDateTimeRepositorySpy $searchEventStepIdAndDateTimeRepositorySpy = null;

    abstract public function eventStepRepositorySpy(): EventStepRepositorySpy;

    abstract public function stepTypeRepositorySpy(): StepTypeRepositorySpy;

    abstract public function eventRepositorySpy(): EventRepositorySpy;

    public function searchEventStepIdAndDateTimeQueryHandler(): SearchEventStepIdAndDateTimeQueryHandler
    {
        if (null === $this->searchEventStepIdAndDateTimeQueryHandler) {
            $this->searchEventStepIdAndDateTimeQueryHandler = new SearchEventStepIdAndDateTimeQueryHandler(
                $this->searchEventStepIdAndDateTimeRepositorySpy(),
            );
        }

        return $this->searchEventStepIdAndDateTimeQueryHandler;
    }

    public function searchEventStepIdAndDateTimeRepositorySpy(): SearchEventStepIdAndDateTimeRepositorySpy
    {
        if (null === $this->searchEventStepIdAndDateTimeRepositorySpy) {
            $this->searchEventStepIdAndDateTimeRepositorySpy = new SearchEventStepIdAndDateTimeRepositorySpy(
                $this->eventStepRepositorySpy(),
                $this->stepTypeRepositorySpy(),
                $this->eventRepositorySpy(),
            );
        }

        return $this->searchEventStepIdAndDateTimeRepositorySpy;
    }
}
