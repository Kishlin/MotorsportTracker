<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache;

use Kishlin\Backend\MotorsportCache\Schedule\Domain\Entity\Schedule;
use Kishlin\Backend\MotorsportCache\Schedule\Domain\ValueObject\SeasonEventList;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final readonly class UpdateSeasonScheduleCacheCommandHandler implements CommandHandler
{
    public function __construct(
        private SeasonScheduleDataGateway $seasonScheduleDataGateway,
        private CachePersister $cachePersister,
    ) {}

    public function __invoke(UpdateSeasonScheduleCacheCommand $command): void
    {
        $schedule = $this->seasonScheduleDataGateway->findEventsForSeason($command->championship(), $command->year());

        $championshipSlug = StringHelper::slugify($command->championship());
        $keyData          = ['championship' => $championshipSlug, 'year' => $command->year()];

        $schedule = Schedule::create(
            SeasonEventList::fromData($schedule->data()),
        );

        $this->cachePersister->save($schedule, $keyData);
    }
}
