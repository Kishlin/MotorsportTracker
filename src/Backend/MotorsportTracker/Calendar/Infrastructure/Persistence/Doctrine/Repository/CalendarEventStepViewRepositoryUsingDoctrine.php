<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\CalendarEventStepViewGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CalendarEventStepViewRepositoryUsingDoctrine extends DoctrineRepository implements CalendarEventStepViewGateway
{
    public function save(CalendarEventStepView $calendarEventStepView): void
    {
        $this->persist($calendarEventStepView);
    }
}
