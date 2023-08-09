<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapStandings;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsTeamDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingTeamGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class StandingsTeamGatewayUsingCurl implements StandingTeamGateway
{
    use MotorsportStatsAPIClient;

    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/teams';

    private const SERIES_PARAMETER = '?seriesClassUuid=%s';

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef, ?string $seriesClassUuid = null): StandingsTeamDTO
    {
        $url = sprintf(self::URL, $seasonRef);

        if (null !== $seriesClassUuid) {
            $url .= sprintf(self::SERIES_PARAMETER, $seriesClassUuid);
        }

        $response = $this->client->fetch($url, $this->headers());

        /**
         * @var array{
         *     standings: array<int, array{
         *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
         *         icon: string,
         *         countryRepresenting: array{name: string, uuid: string, picture: string},
         *         position: int,
         *         points: float,
         *         analytics: array{
         *             classWins: int,
         *             fastestLaps: int,
         *             finalAppearances: int,
         *             finishes1And2: int,
         *             podiums: int,
         *             poles: int,
         *             qualifies1And2: int,
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
         *     }>
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return StandingsTeamDTO::fromData($data);
    }
}
