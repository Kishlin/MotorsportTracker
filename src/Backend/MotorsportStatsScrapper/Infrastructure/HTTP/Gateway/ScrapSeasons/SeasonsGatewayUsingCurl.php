<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapSeasons;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons\SeasonsGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons\SeasonsResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class SeasonsGatewayUsingCurl extends MotorsportStatsAPIClient implements SeasonsGateway
{
    /**
     * @throws JsonException
     */
    public function fetchForChampionship(string $championshipUuid): SeasonsResponse
    {
        $response = $this->fetchFromClient($championshipUuid);

        /** @var array<array{name: string, uuid: string, year: int, endYear: ?int, status: string}> $data */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return SeasonsResponse::withSeasons($data);
    }

    protected function url(int $parametersCount): string
    {
        return 'https://api.motorsportstats.com/widgets/1.0.0/series/%s/seasons';
    }

    protected function topic(): string
    {
        return 'seasons';
    }
}
