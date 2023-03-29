<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\DTO\RacesToComputeDTO;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\RacesToComputeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventSessionRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveSessionTypeRepositorySpy;

final class RacesToComputeRepositorySpy implements RacesToComputeGateway
{
    public function __construct(
        private readonly SaveSessionTypeRepositorySpy $sessionTypeRepositorySpy,
        private readonly EventSessionRepositorySpy $eventSessionRepositorySpy,
    ) {
    }

    public function findRaces(string $eventId): RacesToComputeDTO
    {
        $typeRepositorySpy = $this->sessionTypeRepositorySpy;

        $filteredEventSessions = array_filter(
            $this->eventSessionRepositorySpy->all(),
            static function (EventSession $eventSession) use ($eventId) {
                return $eventId === $eventSession->eventId()->value();
            },
        );

        $mappedRaces = array_map(
            static function (EventSession $eventSession) use ($typeRepositorySpy) {
                return [
                    'id'   => $eventSession->id()->value(),
                    'type' => $typeRepositorySpy->safeGet($eventSession->typeId())->label()->value(),
                ];
            },
            $filteredEventSessions,
        );

        return RacesToComputeDTO::fromList($mappedRaces);
    }
}
