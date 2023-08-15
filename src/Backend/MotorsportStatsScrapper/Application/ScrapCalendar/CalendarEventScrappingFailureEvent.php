<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;
use Throwable;

final readonly class CalendarEventScrappingFailureEvent implements Event
{
    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startDate: ?int,
     *     startTimeUtc: ?int,
     *     endDate: ?int,
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
        private array $event,
        private Throwable $e,
    ) {
    }

    /**
     * @return array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startDate: ?int,
     *     startTimeUtc: ?int,
     *     endDate: ?int,
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

    public function e(): Throwable
    {
        return $this->e;
    }

    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startDate: ?int,
     *     startTimeUtc: ?int,
     *     endDate: ?int,
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
    public static function forEvent(array $event, Throwable $e): self
    {
        return new self($event, $e);
    }
}
