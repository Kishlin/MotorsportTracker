<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Connector;

use Kishlin\Backend\Client\Domain\Client;

final readonly class MotorsportStatsConnector
{
    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @param array<int|string> $parameters
     */
    public function fetch(string $url, array $parameters = []): string
    {
        $url = sprintf($url, ...$parameters);

        return $this->client->fetch($url, $this->headers());
    }

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
