<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime;

use Kishlin\Backend\MotorsportTracker\Event\Domain\View\EventStepIdAndDateTimePOPO;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class SearchEventStepIdAndDateTimeResponse implements Response
{
    private function __construct(
        private EventStepIdAndDateTimePOPO $eventStep,
    ) {
    }

    public function eventStep(): EventStepIdAndDateTimePOPO
    {
        return $this->eventStep;
    }

    public static function fromScalars(EventStepIdAndDateTimePOPO $eventStepIdAndDateTimePOPO): self
    {
        return new self($eventStepIdAndDateTimePOPO);
    }
}
