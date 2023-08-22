<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapStandings;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsConstructorDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway\StandingConstructorGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class StandingsConstructorGatewayUsingCurl extends MotorsportStatsAPIClient implements StandingConstructorGateway
{
    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef, ?string $seriesClassUuid = null): StandingsConstructorDTO
    {
        $response = null === $seriesClassUuid ?
            $this->fetchFromClient($seasonRef) :
            $this->fetchFromClient($seasonRef, $seriesClassUuid);

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

    protected function url(int $parametersCount): string
    {
        if (2 === $parametersCount) {
            return 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/constructors?seriesClassUuid=%s';
        }

        return 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/constructors';
    }

    protected function topic(): string
    {
        return 'standings_constructors';
    }
}
