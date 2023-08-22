<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client;

use Kishlin\Backend\Client\Domain\Client;
use Kishlin\Backend\Client\Domain\Event\ClientRequestEvent;
use Kishlin\Backend\Client\Domain\Event\ClientResponseEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

abstract readonly class MotorsportStatsAPIClient
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private Client $client,
    ) {
    }

    abstract protected function url(int $parametersCount): string;

    abstract protected function topic(): string;

    protected function fetchFromClient(string ...$key): string
    {
        $event = ClientRequestEvent::for($this->topic(), $key);
        $this->eventDispatcher->dispatch($event);

        $response = $event->response();

        if (null === $response) {
            $url = sprintf($this->url(count($key)), ...$key);

            $response = $this->client->fetch($url, $this->headers());

            $this->eventDispatcher->dispatch(
                ClientResponseEvent::for($this->topic(), $key, $response),
            );
        }

        return $response;
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
