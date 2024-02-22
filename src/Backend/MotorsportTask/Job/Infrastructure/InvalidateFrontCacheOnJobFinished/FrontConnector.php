<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Infrastructure\InvalidateFrontCacheOnJobFinished;

use JsonException;
use Kishlin\Backend\Client\Domain\Client;
use Psr\Log\LoggerInterface;

final readonly class FrontConnector
{
    private const INVALIDATE_CACHE_TAG_URL = '%s/api/revalidate?secret=%s&tag=%s';

    public function __construct(
        private Client $client,
        private string $frontHost,
        private string $frontToken,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function invalidateCacheTag(string $tag): void
    {
        $url = sprintf(self::INVALIDATE_CACHE_TAG_URL, $this->frontHost, $this->frontToken, $tag);

        $response = $this->client->post($url);

        try {
            $content = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($content));

            $this->logger?->info(sprintf('Invalidated cache tag: %s', $tag), $content);
        } catch (JsonException) {
            $this->logger?->error(sprintf('Invalid response from front: %s', $response));
        }
    }
}
