<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class SeasonEventsNotFoundException extends DomainException
{
}
