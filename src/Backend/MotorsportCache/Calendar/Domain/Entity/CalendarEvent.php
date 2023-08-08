<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity;

use Exception;
use JsonException;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSessions;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventVenue;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CalendarEvent extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly CalendarEventSeries $series,
        private readonly CalendarEventVenue $venue,
        private readonly CalendarEventSessions $sessions,
        private readonly UuidValueObject $reference,
        private readonly PositiveIntValueObject $index,
        private readonly StringValueObject $slug,
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $shortName,
        private readonly NullableStringValueObject $shortCode,
        private readonly NullableStringValueObject $status,
        private readonly NullableDateTimeValueObject $startDate,
        private readonly NullableDateTimeValueObject $endDate,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function withEntry(UuidValueObject $id, CalendarEventSeries $series, CalendarEventEntry $entry): self
    {
        return new self(
            $id,
            $series,
            $entry->venue(),
            $entry->sessions(),
            $entry->reference(),
            $entry->index(),
            $entry->slug(),
            $entry->name(),
            $entry->shortName(),
            $entry->shortCode(),
            $entry->status(),
            $entry->startDate(),
            $entry->endDate(),
        );
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        CalendarEventSeries $series,
        CalendarEventVenue $venue,
        CalendarEventSessions $sessions,
        UuidValueObject $reference,
        PositiveIntValueObject $index,
        StringValueObject $slug,
        StringValueObject $name,
        NullableStringValueObject $shortName,
        NullableStringValueObject $shortCode,
        NullableStringValueObject $status,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
    ): self {
        return new self($id, $series, $venue, $sessions, $reference, $index, $slug, $name, $shortName, $shortCode, $status, $startDate, $endDate);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function series(): CalendarEventSeries
    {
        return $this->series;
    }

    public function venue(): CalendarEventVenue
    {
        return $this->venue;
    }

    public function sessions(): CalendarEventSessions
    {
        return $this->sessions;
    }

    public function reference(): UuidValueObject
    {
        return $this->reference;
    }

    public function index(): PositiveIntValueObject
    {
        return $this->index;
    }

    public function slug(): StringValueObject
    {
        return $this->slug;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function shortName(): NullableStringValueObject
    {
        return $this->shortName;
    }

    public function shortCode(): NullableStringValueObject
    {
        return $this->shortCode;
    }

    public function status(): NullableStringValueObject
    {
        return $this->status;
    }

    public function startDate(): NullableDateTimeValueObject
    {
        return $this->startDate;
    }

    public function endDate(): NullableDateTimeValueObject
    {
        return $this->endDate;
    }

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'         => $this->id->value(),
            'series'     => $this->series->asString(),
            'venue'      => $this->venue->asString(),
            'sessions'   => $this->sessions->asString(),
            'reference'  => $this->reference->value(),
            'index'      => $this->index->value(),
            'slug'       => $this->slug->value(),
            'name'       => $this->name->value(),
            'short_name' => $this->shortName->value(),
            'short_code' => $this->shortCode->value(),
            'status'     => $this->status->value(),
            'start_date' => $this->startDate->value()?->format('Y-m-d H:i:s'),
            'end_date'   => $this->endDate->value()?->format('Y-m-d H:i:s'),
        ];
    }
}
