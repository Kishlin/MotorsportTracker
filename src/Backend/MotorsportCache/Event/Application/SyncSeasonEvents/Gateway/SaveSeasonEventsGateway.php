<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\Gateway;

use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\SeasonEvents;

interface SaveSeasonEventsGateway
{
    public function save(SeasonEvents $seasonEvents): void;
}
