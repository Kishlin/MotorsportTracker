<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\SaveSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\SeasonEvents;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class SaveSeasonEventsRepository extends CacheRepository implements SaveSeasonEventsGateway
{
    public function save(SeasonEvents $seasonEvents): void
    {
        $this->persist($seasonEvents);
    }
}
