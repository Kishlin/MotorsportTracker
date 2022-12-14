<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class NotAValidMonthAndYearException extends DomainException
{
}
