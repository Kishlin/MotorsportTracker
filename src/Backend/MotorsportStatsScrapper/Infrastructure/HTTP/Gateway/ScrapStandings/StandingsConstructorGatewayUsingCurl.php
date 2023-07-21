<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapStandings;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsConstructorDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingConstructorGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class StandingsConstructorGatewayUsingCurl implements StandingConstructorGateway
{
    use MotorsportStatsAPIClient;

    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/constructors';

    private const SERIES_PARAMETER = '?seriesClassUuid=%s';

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef, ?string $seriesClassUuid = null): StandingsConstructorDTO
    {
        $url = sprintf(self::URL, $seasonRef);

        if (null !== $seriesClassUuid) {
            $url .= sprintf(self::SERIES_PARAMETER, $seriesClassUuid);
        }

        $response = $this->client->fetch($url, $this->headers());

        /**
         * @var array{
         *     standings: array<int, array{
         *         constructor: array{name: string, uuid: string},
         *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string}|null,
         *         countryRepresenting: array{name: string, uuid: string, picture: string}|null,
         *         position: int,
         *         points: float,
         *         analytics: array{wins: int},
         *     }>
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return StandingsConstructorDTO::fromData($data);
    }
}
