<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapSeasonAnalytics;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasonAnalytics\StandingsGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasonAnalytics\StandingsResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final class StandingsGatewayUsingCurl implements StandingsGateway
{
    use MotorsportStatsAPIClient;

    private const url = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/drivers';

    public function __construct(
        private readonly Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef): StandingsResponse
    {
        $url = sprintf(self::url, $seasonRef);

        $response = $this->client->fetch($url, $this->headers());

        /**
         * @var array{
         *     standings: array<array{
         *         driver: array{name: string, uuid: string, shortCode: string, colour: string, picture: string},
         *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
         *         nationality: array{name: string, uuid: string, picture: string},
         *         position: int,
         *         points: float,
         *         analytics: array{
         *             avgFinishPosition: float,
         *             classWins: int,
         *             fastestLaps: int,
         *             finalAppearances: int,
         *             hatTricks: int,
         *             podiums: int,
         *             poles: int,
         *             racesLed: int,
         *             ralliesLed: int,
         *             retirements: int,
         *             semiFinalAppearances: int,
         *             stageWins: int,
         *             starts: int,
         *             top10s: int,
         *             top5s: int,
         *             wins: int,
         *             winsPercentage: float,
         *         },
         *     }>,
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return StandingsResponse::withStandings($data);
    }
}
