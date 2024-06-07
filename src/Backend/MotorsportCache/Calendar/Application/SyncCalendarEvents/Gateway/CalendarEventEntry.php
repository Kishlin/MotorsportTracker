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
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CalendarEventEntry
{
    /**
     * @param array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     country: array{
     *         id: string,
     *         code: string,
     *         name: string,
     *     }
     * } $venue
     * @param array{
     *     id: string,
     *     type: string,
     *     slug: string,
     *     has_result: bool,
     *     start_date: ?string,
     *     end_date: ?string,
     * }[] $sessions
     */
    private function __construct(
        private array $venue,
        private string $reference,
        private int $index,
        private string $slug,
        private string $name,
        private ?string $shortName,
        private ?string $shortCode,
        private ?string $status,
        private ?string $startDate,
        private ?string $endDate,
        private array $sessions,
    ) {}

    public function venue(): CalendarEventVenue
    {
        return new CalendarEventVenue($this->venue);
    }

    public function reference(): UuidValueObject
    {
        return new UuidValueObject($this->reference);
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

    public function shortCode(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->shortCode);
    }

    public function status(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->status);
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
     *         id: string,
     *         name: string,
     *         slug: string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         }
     *     },
     *     reference: string,
     *     index: int,
     *     name: string,
     *     slug: string,
     *     short_name: ?string,
     *     short_code: ?string,
     *     status: ?string,
     *     start_date: ?string,
     *     end_date: ?string,
     *     sessions: array{
     *         id: string,
     *         slug: string,
     *         type: string,
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
            $data['reference'],
            $data['index'],
            $data['slug'],
            $data['name'],
            $data['short_name'],
            $data['short_code'],
            $data['status'],
            $data['start_date'],
            $data['end_date'],
            $data['sessions'],
        );
    }
}
