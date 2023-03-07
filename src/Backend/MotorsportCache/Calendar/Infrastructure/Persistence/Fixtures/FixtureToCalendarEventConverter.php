<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSessions;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventVenue;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToCalendarEventConverter implements FixtureConverter
{
    /**
     * @throws Exception
     */
    public function convert(Fixture $fixture): AggregateRoot
    {
        return CalendarEvent::instance(
            new UuidValueObject($fixture->identifier()),
            CalendarEventSeries::fromData($this->decodeSeries($fixture->getString('series'))),
            CalendarEventVenue::fromData($this->decodeVenue($fixture->getString('venue'))),
            CalendarEventSessions::fromData($this->decodeSessions($fixture->getString('sessions'))),
            new PositiveIntValueObject($fixture->getInt('index')),
            new StringValueObject($fixture->getString('slug')),
            new StringValueObject($fixture->getString('name')),
            new NullableStringValueObject($fixture->getString('shortName')),
            new NullableDateTimeValueObject($fixture->getDateTime('startDate')),
            new NullableDateTimeValueObject($fixture->getDateTime('endDate')),
        );
    }

    /**
     * @return array{
     *     name: string,
     *     slug: string,
     *     year: int,
     *     icon: string,
     *     color: string,
     * }
     */
    private function decodeSeries(string $data): array
    {
        /**
         * @var array{
         *     name: string,
         *     slug: string,
         *     year: int,
         *     icon: string,
         *     color: string,
         * } $decoded
         */
        $decoded = json_decode($data, true);
        assert(is_array($decoded));

        return $decoded;
    }

    /**
     * @return array{
     *     name: string,
     *     slug: string,
     *     country: array{
     *         code: string,
     *         name: string,
     *     }
     * }
     */
    private function decodeVenue(string $data): array
    {
        /**
         * @var array{
         *     name: string,
         *     slug: string,
         *     country: array{
         *         code: string,
         *         name: string,
         *     }
         * } $decoded
         */
        $decoded = json_decode($data, true);
        assert(is_array($decoded));

        return $decoded;
    }

    /**
     * @return array{
     *     type: string,
     *     slug: string,
     *     has_result: bool,
     *     start_date: ?int,
     *     end_date: ?int,
     * }[]
     */
    private function decodeSessions(string $data): array
    {
        /**
         * @var array{
         *     type: string,
         *     slug: string,
         *     has_result: bool,
         *     start_date: ?int,
         *     end_date: ?int,
         * }[] $decoded
         */
        $decoded = json_decode($data, true);
        assert(is_array($decoded));

        return $decoded;
    }
}
