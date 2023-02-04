<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar;

use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\CalendarEventStepViewGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property CalendarEventStepView[] $objects
 *
 * @method CalendarEventStepView[]    all()
 * @method null|CalendarEventStepView get(UuidValueObject $id)
 * @method CalendarEventStepView      safeGet(UuidValueObject $id)
 */
final class CalendarEventStepViewRepositorySpy extends AbstractRepositorySpy implements CalendarEventStepViewGateway
{
    public function save(CalendarEventStepView $calendarEventStepView): void
    {
        $this->add($calendarEventStepView);
    }
}
