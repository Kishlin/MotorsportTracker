<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\Gateway\SaveSeasonEventsGateway;
use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\SeasonEvents;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class SaveSeasonEventsRepository extends CacheRepository implements SaveSeasonEventsGateway
{
    public function save(SeasonEvents $seasonEvents): void
    {
        $this->persist($seasonEvents);
    }
}
