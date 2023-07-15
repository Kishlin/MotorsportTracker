<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapStandings;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsDataDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingsGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class StandingsGatewayUsingCurl implements StandingsGateway
{
    use MotorsportStatsAPIClient;

    private const url = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings';

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetchStandingsDataForSeason(string $seasonRef): StandingsDataDTO
    {
        $url = sprintf(self::url, $seasonRef);

        $response = $this->client->fetch($url, $this->headers());

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
}
