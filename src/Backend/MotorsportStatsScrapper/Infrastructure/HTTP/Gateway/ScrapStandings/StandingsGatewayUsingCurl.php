<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapStandings;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsDataDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingsGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class StandingsGatewayUsingCurl extends MotorsportStatsAPIClient implements StandingsGateway
{
    /**
     * @throws JsonException
     */
    public function fetchStandingsDataForSeason(string $seasonRef): StandingsDataDTO
    {
        $response = $this->fetchFromClient($seasonRef);

        /**
         * @var array{
         *     driverStandings: array<int, null|array{name: string, uuid: string}>,
         *     teamStandings: array<int, null|array{name: string, uuid: string}>,
         *     constructorStandings: array<int, null|array{name: string, uuid: string}>,
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return StandingsDataDTO::fromData($data);
    }

    protected function url(int $parametersCount): string
    {
        return 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings';
    }

    protected function topic(): string
    {
        return 'standings';
    }
}
