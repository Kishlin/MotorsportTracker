<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;

final readonly class CachedClientUsingCurl implements Client
{
    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
        private LoggerInterface $logger,
    ) {
    }

    public function fetch(string $url, array $headers = []): string
    {
        try {
            $item = $this->cacheItemPool->getItem($this->computeKey($url));
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e->getMessage());

            throw new RuntimeException('InvalidArgumentException thrown from cache.');
        }

        if (false === $item->isHit()) {
            $this->logger->info(sprintf('No content found in cache for %s. Requesting.', $url));

            $content = $this->doFetch($headers, $url);

            $item->set($content);

            $this->cacheItemPool->save($item);
        } else {
            $this->logger->info(sprintf('Got content from cache for %s.', $url));
        }

        $content = $item->get();

        assert(is_string($content));

        return $content;
    }

    /**
     * @param array<int, string> $headers
     */
    private function doFetch(array $headers, string $url): string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        curl_close($ch);

        assert(is_string($result));

        return $result;
    }

    private function computeKey(string $url): string
    {
        return 'client-' . urlencode($url);
    }
}
