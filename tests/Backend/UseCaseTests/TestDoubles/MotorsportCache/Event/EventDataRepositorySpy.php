<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Event;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\EventDataDTO;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway\EventDataGateway;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveEventRepositorySpy;

final class EventDataRepositorySpy implements EventDataGateway
{
    public function __construct(
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
        private readonly SaveSeasonRepositorySpy $seasonRepositorySpy,
        private readonly SaveEventRepositorySpy $eventRepositorySpy,
    ) {
    }

    public function findAll(): EventDataDTO
    {
        $eventData = [];

        foreach ($this->eventRepositorySpy->all() as $event) {
            $season       = $this->seasonRepositorySpy->safeGet($event->seasonId());
            $championship = $this->championshipRepositorySpy->safeGet($season->championshipId());

            $eventData[] = [
                'championship' => $championship->name()->value(),
                'year'         => $season->year()->value(),
                'event'        => $event->name()->value(),
            ];
        }

        return EventDataDTO::fromData($eventData);
    }
}
