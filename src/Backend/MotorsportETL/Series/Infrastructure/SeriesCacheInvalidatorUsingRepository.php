<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Infrastructure;

use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\SeriesCacheInvalidator;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Psr\Log\LoggerInterface;

final readonly class SeriesCacheInvalidatorUsingRepository implements SeriesCacheInvalidator
{
    public function __construct(
        private CacheInvalidatorGateway $cacheInvalidatorRepository,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function invalidate(): void
    {
        $this->logger?->info('Invalidating series cache');

        $this->cacheInvalidatorRepository->invalidate(Context::SERIES->value, '/');
    }
}
