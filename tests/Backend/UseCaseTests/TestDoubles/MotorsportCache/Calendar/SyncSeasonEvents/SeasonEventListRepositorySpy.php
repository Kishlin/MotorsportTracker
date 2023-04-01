<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\SeasonEventListGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\SeasonEventListDTO;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveEventRepositorySpy;

final class SeasonEventListRepositorySpy implements SeasonEventListGateway
{
    public function __construct(
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
        private readonly SaveSeasonRepositorySpy $seasonRepositorySpy,
        private readonly SaveEventRepositorySpy $eventRepositorySpy,
    ) {
    }

    public function findEventsForSeason(StringValueObject $championship, StrictlyPositiveIntValueObject $year): SeasonEventListDTO
    {
        $championshipId = $this->championshipRepositorySpy->findIfExists($championship, new NullableUuidValueObject(null));
        if (null === $championshipId) {
            return SeasonEventListDTO::fromData([]);
        }

        $seasonId = $this->seasonRepositorySpy->find($championshipId->value(), $year->value());
        if (null === $seasonId) {
            return SeasonEventListDTO::fromData([]);
        }

        $events = [];
        foreach ($this->eventRepositorySpy->all() as $event) {
            if (false === $seasonId->equals($event->seasonId())) {
                continue;
            }

            $slug = StringHelper::slugify($event->name()->value());

            $events[$slug] = [
                'id'    => $event->id()->value(),
                'slug'  => $slug,
                'name'  => $event->name()->value(),
                'index' => $event->index()->value(),
            ];
        }

        return SeasonEventListDTO::fromData($events);
    }
}
