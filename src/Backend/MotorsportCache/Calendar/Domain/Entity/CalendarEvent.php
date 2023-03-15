<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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
        private readonly PositiveIntValueObject $index,
        private readonly StringValueObject $slug,
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $shortName,
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
            $entry->index(),
            $entry->slug(),
            $entry->name(),
            $entry->shortName(),
            $entry->startDate(),
            $entry->endDate(),
        );
    }

    public static function instance(
        UuidValueObject $id,
        CalendarEventSeries $series,
        CalendarEventVenue $venue,
        CalendarEventSessions $sessions,
        PositiveIntValueObject $index,
        StringValueObject $slug,
        StringValueObject $name,
        NullableStringValueObject $shortName,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
    ): self {
        return new self($id, $series, $venue, $sessions, $index, $slug, $name, $shortName, $startDate, $endDate);
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
            'index'      => $this->index->value(),
            'slug'       => $this->slug->value(),
            'name'       => $this->name->value(),
            'short_name' => $this->shortName->value(),
            'start_date' => $this->startDate->value()?->format('Y-m-d H:i:s'),
            'end_date'   => $this->endDate->value()?->format('Y-m-d H:i:s'),
        ];
    }
}
