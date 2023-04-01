<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\SeasonEvents;

interface SaveSeasonEventsGateway
{
    public function save(SeasonEvents $seasonEvents): void;
}
