<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client;

trait MotorsportStatsAPIClient
{
    /**
     * @return array<int, string>
     */
    private function headers(): array
    {
        return [
            'Origin: https://widgets.motorsportstats.com',
            'X-Parent-Referer: https://motorsportstats.com/',
        ];
    }
}
