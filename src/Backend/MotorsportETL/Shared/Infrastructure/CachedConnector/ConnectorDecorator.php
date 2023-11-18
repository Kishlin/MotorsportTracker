<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector;

use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Connector\MotorsportStatsConnector;
use Psr\Log\LoggerInterface;

final readonly class ConnectorDecorator implements Connector
{
    public function __construct(
        private ConnectorResponseWriteRepository $writeRepository,
        private ConnectorResponseReadRepository $readRepository,
        private StringHashTool $stringConversionsTool,
        private MotorsportStatsConnector $decorated,
        private ContextFinder $contextFinder,
        private ?LoggerInterface $logger,
    ) {
    }

    public function fetch(string $url, array $parameters = []): string
    {
        $parametersHash = $this->stringConversionsTool->parametersToKey($parameters);
        $urlContext     = $this->contextFinder->urlToContext($url);

        $cachedResponse = $this->readRepository->findResponse($urlContext, $parametersHash);

        if (null !== $cachedResponse) {
            $this->logger?->info('Using cached response ' . implode(' ', $parameters));

            return $this->stringConversionsTool->decryptCachedResponse($cachedResponse);
        }

        $response = $this->doFetch($url, $parameters);

        $encrypted = $this->stringConversionsTool->encryptPlainTextResponse($response);
        $this->writeRepository->save($urlContext, $parametersHash, $encrypted);

        return $response;
    }

    /**
     * @param array<int|string> $parameters
     */
    private function doFetch(string $url, array $parameters = []): string
    {
        $this->logger?->info('Fetching from API ' . implode(' ', $parameters));

        return $this->decorated->fetch($url, $parameters);
    }
}
