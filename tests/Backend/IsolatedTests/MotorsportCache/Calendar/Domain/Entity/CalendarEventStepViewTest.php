<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportCache\Calendar\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewChampionshipSlug;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewColor;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewIcon;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewId;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewName;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewReferenceId;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewType;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewVenueLabel;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEventStepView
 */
final class CalendarEventStepViewTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = 'd9e841a9-1a8b-4796-835b-c48f46ebe949';
        $slug      = 'slug';
        $color     = 'color';
        $icon      = 'icon';
        $name      = 'name';
        $venue     = 'venue';
        $type      = 'type';
        $dateTime  = new \DateTimeImmutable();
        $reference = '89869769-05fb-43e2-8608-77bf3915f9c5';

        $entity = CalendarEventStepView::instance(
            new CalendarEventStepViewId($id),
            new CalendarEventStepViewChampionshipSlug($slug),
            new CalendarEventStepViewColor($color),
            new CalendarEventStepViewIcon($icon),
            new CalendarEventStepViewName($name),
            new CalendarEventStepViewVenueLabel($venue),
            new CalendarEventStepViewType($type),
            new CalendarEventStepViewDateTime($dateTime),
            new CalendarEventStepViewReferenceId($reference),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($slug, $entity->championshipSlug());
        self::assertValueObjectSame($color, $entity->color());
        self::assertValueObjectSame($icon, $entity->icon());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($venue, $entity->venueLabel());
        self::assertValueObjectSame($type, $entity->type());
        self::assertValueObjectSame($dateTime, $entity->dateTime());
        self::assertValueObjectSame($reference, $entity->reference());
    }
}
