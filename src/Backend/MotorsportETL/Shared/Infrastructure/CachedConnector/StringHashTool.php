<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector;

final class StringHashTool
{
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
