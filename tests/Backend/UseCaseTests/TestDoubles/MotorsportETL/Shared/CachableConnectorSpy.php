<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared;

use Exception;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector\ContextFinder;

final class CachableConnectorSpy implements Connector, CacheInvalidatorGateway
{
    private int $actualRequestsCounter = 0;

    /** @var array<string, array<string, int>> */
    private array $cacheInvalidations = [];

    /** @var array<string, array<string, string>> */
    private array $preparedResponses = [];

    /** @var array<string, array<string, string>> */
    private array $cache = [];

    public function __construct(
        private readonly ContextFinder $contextFinder
    ) {}

    public function resetState(): void
    {
        $this->actualRequestsCounter = 0;
        $this->preparedResponses     = [];
        $this->cacheInvalidations    = [];
        $this->cache                 = [];
    }

    /**
     * @param array<int|string> $parameters
     */
    public function prepareResponse(string $context, array $parameters, string $response): void
    {
        $parametersKey = $this->computeParametersKey($parameters);

        $this->preparedResponses[$context][$parametersKey] = $response;
    }

    public function actualRequestsCount(): int
    {
        return $this->actualRequestsCounter;
    }

    /**
     * @param array<int|string> $parameters
     */
    public function prepareCache(string $context, array $parameters, string $response): void
    {
        $this->cacheResponse($context, $parameters, $response);
    }

    /**
     * @param array<int|string> $parameters
     */
    public function hasCached(string $context, array $parameters): bool
    {
        $parametersKey = $this->computeParametersKey($parameters);

        return isset($this->cache[$context][$parametersKey]);
    }

    /**
     * @param array<int|string> $parameters
     */
    public function timesItInvalidatedCache(string $context, array $parameters = []): int
    {
        $parametersKey = $this->computeParametersKey($parameters);

        return $this->cacheInvalidations[$context][$parametersKey] ?? 0;
    }

    /**
     * @throws Exception
     */
    public function invalidate(string $table, string $key): void
    {
        $this->cacheInvalidations[$table][$key] = ($this->cacheInvalidations[$table][$key] ?? 0) + 1;

        unset($this->cache[$table][$key]);
    }

    /**
     * @throws Exception
     */
    public function fetch(string $url, array $parameters = []): string
    {
        $context  = $this->contextFinder->urlToContext($url)->value;
        $response = $this->getFromCache($context, $parameters);

        if (null !== $response) {
            return $response;
        }

        ++$this->actualRequestsCounter;

        $response = $this->getFromPreparedResponse($context, $parameters);

        if (null === $response) {
            throw new Exception("Cannot find content for url {$url}");
        }

        $this->cacheResponse($context, $parameters, $response);

        return $response;
    }

    /**
     * @param array<int|string> $parameters
     */
    private function cacheResponse(string $context, array $parameters, string $response): void
    {
        $parametersKey = $this->computeParametersKey($parameters);

        $this->cache[$context][$parametersKey] = $response;
    }

    /**
     * @param array<int|string> $parameters
     */
    private function getFromCache(string $context, array $parameters): ?string
    {
        if (false === isset($this->cache[$context])) {
            return null;
        }

        $parametersKey = $this->computeParametersKey($parameters);

        if (false === isset($this->cache[$context][$parametersKey])) {
            return null;
        }

        return $this->cache[$context][$parametersKey];
    }

    /**
     * @param array<int|string> $parameters
     */
    private function getFromPreparedResponse(string $context, array $parameters): ?string
    {
        if (false === isset($this->preparedResponses[$context])) {
            return null;
        }

        $parametersKey = $this->computeParametersKey($parameters);

        if (false === isset($this->preparedResponses[$context][$parametersKey])) {
            return null;
        }

        return $this->preparedResponses[$context][$parametersKey];
    }

    /**
     * @param array<int|string> $parameters
     */
    private function computeParametersKey(array $parameters): string
    {
        if (empty($parameters)) {
            return '/';
        }

        return implode('_', $parameters);
    }
}
