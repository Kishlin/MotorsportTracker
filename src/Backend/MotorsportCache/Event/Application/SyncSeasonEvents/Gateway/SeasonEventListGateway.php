<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\Gateway;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncSeasonEvents\SeasonEventListDTO;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

interface SeasonEventListGateway
{
    public function findEventsForSeason(StringValueObject $championship, StrictlyPositiveIntValueObject $year): SeasonEventListDTO;
}
