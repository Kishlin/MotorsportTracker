<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapStandings;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsDriverDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingDriverGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class StandingsDriverGatewayUsingCurl implements StandingDriverGateway
{
    use MotorsportStatsAPIClient;

    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/drivers/details';

    private const SERIES_PARAMETER = '?seriesClassUuid=%s';

    public function __construct(
        private Client $client,
    )  {
    }

    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef, ?string $seriesClassUuid = null): StandingsDriverDTO
    {
        $url = sprintf(self::URL, $seasonRef);

        if (null !== $seriesClassUuid) {
            $url .= sprintf(self::SERIES_PARAMETER, $seriesClassUuid);
        }

        $response = $this->client->fetch($url, $this->headers());

        /**
         * @var array{
         *     standings: array<int, array{
         *         driver: array{name: string, firstName: string, lastName: string, shortCode: string, uuid: string},
         *         nationality: array{name: string, uuid: string, picture: string},
         *         team: array{name: string, uuid: string},
         *         position: int,
         *         totalPoints: float,
         *         raceResults: array<int, array{points: float, finishPosition: int, seasonPoints: float, seasonPosition: int}>
         *     }>
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return StandingsDriverDTO::fromData($data);
    }
}
