<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapStandings;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsDriverDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingDriverGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class StandingsDriverGatewayUsingCurl extends MotorsportStatsAPIClient implements StandingDriverGateway
{
    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef, ?string $seriesClassUuid = null): StandingsDriverDTO
    {
        $response = null === $seriesClassUuid ?
            $this->fetchFromClient($seasonRef) :
            $this->fetchFromClient($seasonRef, $seriesClassUuid);

        /**
         * @var array{
         *     standings: array<int, array{
         *         driver: array{name: string, shortCode: string, uuid: string},
         *         nationality: array{name: string, uuid: string, picture: string},
         *         team: array{name: string, uuid: string},
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
         *     }>
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return StandingsDriverDTO::fromData($data);
    }

    protected function url(int $parametersCount): string
    {
        if (2 === $parametersCount) {
            return 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/drivers?seriesClassUuid=%s';
        }

        return 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/drivers';
    }

    protected function topic(): string
    {
        return 'standings_drivers';
    }
}
