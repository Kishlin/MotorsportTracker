<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\Shared\Domain\Tools;
use RuntimeException;

final class StringHashTool
{
    public function urlToContext(string $url): Context
    {
        if (Tools::endsWith($url, 'series')) {
            return Context::SERIES;
        }

        if (Tools::endsWith($url, 'seasons')) {
            return Context::SEASONS;
        }

        throw new RuntimeException("Unknown context for url: {$url}");
    }

    /**
     * @param array<int|string> $parameters
     */
    public function parametersToKey(array $parameters): string
    {
        if (empty($parameters)) {
            return '/';
        }

        return implode('_', $parameters);
    }

    public function encryptPlainTextResponse(string $response): string
    {
        $compressed = gzcompress($response, 9);
        assert(false !== $compressed);

        return base64_encode($compressed);
    }

    public function decryptCachedResponse(string $cachedResponse): string
    {
        $decoded = base64_decode($cachedResponse, true);
        assert(false !== $decoded);

        $uncompressed = gzuncompress($decoded);
        assert(false !== $uncompressed);

        return $uncompressed;
    }
}
