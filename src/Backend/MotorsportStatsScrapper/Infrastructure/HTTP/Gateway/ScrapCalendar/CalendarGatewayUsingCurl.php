<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapCalendar;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar\CalendarGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar\CalendarResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final class CalendarGatewayUsingCurl implements CalendarGateway
{
    use MotorsportStatsAPIClient;

    private const url = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/calendar';

    public function __construct(
        private readonly Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef): CalendarResponse
    {
        $url = sprintf(self::url, $seasonRef);

        $response = $this->client->fetch($url, $this->headers());

        /**
         * @var array{
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
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return CalendarResponse::withCalendar($data);
    }
}
