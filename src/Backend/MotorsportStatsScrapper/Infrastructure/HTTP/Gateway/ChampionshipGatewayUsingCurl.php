<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway;

use Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship\ChampionshipGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship\SyncChampionshipHTTPResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;

final class ChampionshipGatewayUsingCurl implements ChampionshipGateway
{
    private const URL_TEMPLATE = 'https://motorsportstats.com/_next/data/lsVIRmXxl3G1s77SX0kjm/series/%s/calendar/%d.json?slug=formula-one&season=%d';

    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function fetch(string $seriesSlug, int $year): SyncChampionshipHTTPResponse
    {
        $url = sprintf(self::URL_TEMPLATE, $seriesSlug, $year, $year);

        $result = $this->client->fetch($url);

        return SyncChampionshipHTTPResponse::fromResponse($result);
    }
}
