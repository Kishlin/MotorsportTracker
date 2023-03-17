<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapSeasons;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons\SeasonsGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons\SeasonsResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final class SeasonsGatewayUsingCurl implements SeasonsGateway
{
    use MotorsportStatsAPIClient;

    private const url = 'https://api.motorsportstats.com/widgets/1.0.0/series/%s/seasons';

    public function __construct(
        private readonly Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetchForChampionship(string $championshipUuid): SeasonsResponse
    {
        $url = sprintf(self::url, $championshipUuid);

        $response = $this->client->fetch($url, $this->headers());

        /** @var array<array{name: string, uuid: string, year: int, endYear: ?int, status: string}> $data */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return SeasonsResponse::withSeasons($data);
    }
}
