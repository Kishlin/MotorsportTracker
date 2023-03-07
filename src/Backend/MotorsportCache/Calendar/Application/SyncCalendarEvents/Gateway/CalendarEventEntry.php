<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSessions;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventVenue;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class CalendarEventEntry
{
    /**
     * @param array{
     *     name: string,
     *     slug: string,
     *     country: array{
     *         code: string,
     *         name: string,
     *     }
     * } $venue
     * @param array{
     *     type: string,
     *     slug: string,
     *     has_result: bool,
     *     start_date: ?string,
     *     end_date: ?string,
     * }[] $sessions
     */
    private function __construct(
        private readonly array $venue,
        private readonly int $index,
        private readonly string $slug,
        private readonly string $name,
        private readonly ?string $shortName,
        private readonly ?string $startDate,
        private readonly ?string $endDate,
        private readonly array $sessions,
    ) {
    }

    public function venue(): CalendarEventVenue
    {
        return new CalendarEventVenue($this->venue);
    }

    public function index(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->index);
    }

    public function slug(): StringValueObject
    {
        return new StringValueObject($this->slug);
    }

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function shortName(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->shortName);
    }

    /**
     * @throws Exception
     */
    public function startDate(): NullableDateTimeValueObject
    {
        if (null === $this->startDate) {
            return new NullableDateTimeValueObject(null);
        }

        return new NullableDateTimeValueObject(new DateTimeImmutable($this->startDate));
    }

    /**
     * @throws Exception
     */
    public function endDate(): NullableDateTimeValueObject
    {
        if (null === $this->endDate) {
            return new NullableDateTimeValueObject(null);
        }

        return new NullableDateTimeValueObject(new DateTimeImmutable($this->endDate));
    }

    public function sessions(): CalendarEventSessions
    {
        return new CalendarEventSessions($this->sessions);
    }

    /**
     * @param array{
     *     venue: array{
     *         name: string,
     *         slug: string,
     *         country: array{
     *             code: string,
     *             name: string,
     *         }
     *     },
     *     index: int,
     *     slug: string,
     *     name: string,
     *     short_name: ?string,
     *     start_date: ?string,
     *     end_date: ?string,
     *     sessions: array{
     *         type: string,
     *         slug: string,
     *         has_result: bool,
     *         start_date: ?string,
     *         end_date: ?string,
     *     }[]
     * } $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['venue'],
            $data['index'],
            $data['slug'],
            $data['name'],
            $data['short_name'],
            $data['start_date'],
            $data['end_date'],
            $data['sessions'],
        );
    }
}
