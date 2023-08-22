<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapSeries;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeries\SeriesGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeries\SeriesGetawayResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class SeriesGatewayUsingCurl extends MotorsportStatsAPIClient implements SeriesGateway
{
    /**
     * @throws JsonException
     */
    public function fetch(): SeriesGetawayResponse
    {
        $response = $this->fetchFromClient('/');

        /** @var array<array{name: string, uuid: string, shortName: ?string, shortCode: string, category: ?string}> $data */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return SeriesGetawayResponse::withData($data);
    }

    protected function url(int $parametersCount): string
    {
        return 'https://api.motorsportstats.com/widgets/1.0.0/series';
    }

    protected function topic(): string
    {
        return 'series';
    }
}
