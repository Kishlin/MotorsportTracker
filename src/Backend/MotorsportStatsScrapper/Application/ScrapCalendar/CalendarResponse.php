<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

final class CalendarResponse
{
    /**
     * @param array{
     *     season: array{
     *         uuid: string,
     *         year: int,
     *         endYear: int,
     *     },
     *     events: array<int, array{
     *         uuid: string,
     *         name: string,
     *         shortName: string,
     *         shortCode: string,
     *         status: string,
     *         startDate: ?int,
     *         startTimeUtc: ?int,
     *         endDate: ?int,
     *         endTimeUtc: ?int,
     *         venue: array{
     *             name: string,
     *             uuid: string,
     *             shortName: string,
     *             shortCode: string,
     *             picture: string,
     *         },
     *         country: array{
     *             name: string,
     *             uuid: string,
     *             picture: string,
     *         },
     *         sessions: array<array{
     *             uuid: string,
     *             name: string,
     *             shortName: string,
     *             shortCode: string,
     *             status: string,
     *             hasResults: bool,
     *             startTime: ?int,
     *             startTimeUtc: ?int,
     *             endTime: ?int,
     *             endTimeUtc: ?int,
     *         }>
     *     }>
     * } $data
     */
    private function __construct(
        private readonly array $data,
    ) {
    }

    /**
     * @return array{
     *     season: array{
     *         uuid: string,
     *         year: int,
     *         endYear: int,
     *     },
     *     events: array<int, array{
     *         uuid: string,
     *         name: string,
     *         shortName: string,
     *         shortCode: string,
     *         status: string,
     *         startDate: ?int,
     *         startTimeUtc: ?int,
     *         endDate: ?int,
     *         endTimeUtc: ?int,
     *         venue: array{
     *             name: string,
     *             uuid: string,
     *             shortName: string,
     *             shortCode: string,
     *             picture: string,
     *         },
     *         country: array{
     *             name: string,
     *             uuid: string,
     *             picture: string,
     *         },
     *         sessions: array<array{
     *             uuid: string,
     *             name: string,
     *             shortName: string,
     *             shortCode: string,
     *             status: string,
     *             hasResults: bool,
     *             startTime: ?int,
     *             startTimeUtc: ?int,
     *             endTime: ?int,
     *             endTimeUtc: ?int,
     *         }>
     *     }>
     * }
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array{
     *     season: array{
     *         uuid: string,
     *         year: int,
     *         endYear: int,
     *     },
     *     events: array<int, array{
     *         uuid: string,
     *         name: string,
     *         shortName: string,
     *         shortCode: string,
     *         status: string,
     *         startDate: ?int,
     *         startTimeUtc: ?int,
     *         endDate: ?int,
     *         endTimeUtc: ?int,
     *         venue: array{
     *             name: string,
     *             uuid: string,
     *             shortName: string,
     *             shortCode: string,
     *             picture: string,
     *         },
     *         country: array{
     *             name: string,
     *             uuid: string,
     *             picture: string,
     *         },
     *         sessions: array<array{
     *             uuid: string,
     *             name: string,
     *             shortName: string,
     *             shortCode: string,
     *             status: string,
     *             hasResults: bool,
     *             startTime: ?int,
     *             startTimeUtc: ?int,
     *             endTime: ?int,
     *             endTimeUtc: ?int,
     *         }>
     *     }>
     * } $data
     */
    public static function withCalendar(array $data): self
    {
        return new self($data);
    }
}
