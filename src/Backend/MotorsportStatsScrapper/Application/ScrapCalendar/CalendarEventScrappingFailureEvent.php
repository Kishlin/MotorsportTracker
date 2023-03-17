<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final class CalendarEventScrappingFailureEvent implements Event
{
    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startTime: ?int,
     *     startTimeUtc: ?int,
     *     endTime: ?int,
     *     endTimeUtc: ?int,
     *     venue: array{
     *         name: string,
     *         uuid: string,
     *         shortName: string,
     *         shortCode: string,
     *         picture: string,
     *     },
     *     country: array{
     *         name: string,
     *         uuid: string,
     *         picture: string,
     *     },
     *     sessions: array<array{
     *         uuid: string,
     *         name: string,
     *         shortName: string,
     *         shortCode: string,
     *         status: string,
     *         hasResults: bool,
     *         startTime: ?int,
     *         startTimeUtc: ?int,
     *         endTime: ?int,
     *         endTimeUtc: ?int,
     *     }>
     * } $event
     */
    private function __construct(
        private readonly array $event,
    ) {
    }

    /**
     * @return array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startTime: ?int,
     *     startTimeUtc: ?int,
     *     endTime: ?int,
     *     endTimeUtc: ?int,
     *     venue: array{
     *         name: string,
     *         uuid: string,
     *         shortName: string,
     *         shortCode: string,
     *         picture: string,
     *     },
     *     country: array{
     *         name: string,
     *         uuid: string,
     *         picture: string,
     *     },
     *     sessions: array<array{
     *         uuid: string,
     *         name: string,
     *         shortName: string,
     *         shortCode: string,
     *         status: string,
     *         hasResults: bool,
     *         startTime: ?int,
     *         startTimeUtc: ?int,
     *         endTime: ?int,
     *         endTimeUtc: ?int,
     *     }>
     * } $event
     */
    public function event(): array
    {
        return $this->event;
    }

    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startTime: ?int,
     *     startTimeUtc: ?int,
     *     endTime: ?int,
     *     endTimeUtc: ?int,
     *     venue: array{
     *         name: string,
     *         uuid: string,
     *         shortName: string,
     *         shortCode: string,
     *         picture: string,
     *     },
     *     country: array{
     *         name: string,
     *         uuid: string,
     *         picture: string,
     *     },
     *     sessions: array<array{
     *         uuid: string,
     *         name: string,
     *         shortName: string,
     *         shortCode: string,
     *         status: string,
     *         hasResults: bool,
     *         startTime: ?int,
     *         startTimeUtc: ?int,
     *         endTime: ?int,
     *         endTimeUtc: ?int,
     *     }>
     * } $event
     */
    public static function forEvent(array $event): self
    {
        return new self($event);
    }
}
