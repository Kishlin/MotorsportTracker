<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewChampionshipSlug;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewColor;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewDateTime;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewIcon;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewId;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewName;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewReferenceId;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewType;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventStepViewVenueLabel;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class CalendarEventStepView extends AggregateRoot
{
    public function __construct(
        private readonly CalendarEventStepViewId $id,
        private readonly CalendarEventStepViewChampionshipSlug $championshipSlug,
        private readonly CalendarEventStepViewColor $color,
        private readonly CalendarEventStepViewIcon $icon,
        private readonly CalendarEventStepViewName $name,
        private readonly CalendarEventStepViewVenueLabel $venueLabel,
        private readonly CalendarEventStepViewType $type,
        private readonly CalendarEventStepViewDateTime $dateTime,
        private readonly CalendarEventStepViewReferenceId $reference,
    ) {
    }

    public static function create(
        CalendarEventStepViewId $id,
        CalendarEventStepViewChampionshipSlug $championshipSlug,
        CalendarEventStepViewColor $color,
        CalendarEventStepViewIcon $icon,
        CalendarEventStepViewName $name,
        CalendarEventStepViewVenueLabel $venueLabel,
        CalendarEventStepViewType $type,
        CalendarEventStepViewDateTime $dateTime,
        CalendarEventStepViewReferenceId $reference,
    ): self {
        return new self($id, $championshipSlug, $color, $icon, $name, $venueLabel, $type, $dateTime, $reference);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        CalendarEventStepViewId $id,
        CalendarEventStepViewChampionshipSlug $championshipSlug,
        CalendarEventStepViewColor $color,
        CalendarEventStepViewIcon $icon,
        CalendarEventStepViewName $name,
        CalendarEventStepViewVenueLabel $venueLabel,
        CalendarEventStepViewType $type,
        CalendarEventStepViewDateTime $dateTime,
        CalendarEventStepViewReferenceId $reference,
    ): self {
        return new self($id, $championshipSlug, $color, $icon, $name, $venueLabel, $type, $dateTime, $reference);
    }

    public function id(): CalendarEventStepViewId
    {
        return $this->id;
    }

    public function championshipSlug(): CalendarEventStepViewChampionshipSlug
    {
        return $this->championshipSlug;
    }

    public function color(): CalendarEventStepViewColor
    {
        return $this->color;
    }

    public function icon(): CalendarEventStepViewIcon
    {
        return $this->icon;
    }

    public function name(): CalendarEventStepViewName
    {
        return $this->name;
    }

    public function venueLabel(): CalendarEventStepViewVenueLabel
    {
        return $this->venueLabel;
    }

    public function type(): CalendarEventStepViewType
    {
        return $this->type;
    }

    public function dateTime(): CalendarEventStepViewDateTime
    {
        return $this->dateTime;
    }

    public function reference(): CalendarEventStepViewReferenceId
    {
        return $this->reference;
    }
}