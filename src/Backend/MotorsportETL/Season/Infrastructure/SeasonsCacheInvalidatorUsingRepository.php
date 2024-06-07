<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Infrastructure;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeasonsCacheInvalidator;
use Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject\SeriesIdentity;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector\CacheInvalidatorRepository;
use Psr\Log\LoggerInterface;

final readonly class SeasonsCacheInvalidatorUsingRepository implements SeasonsCacheInvalidator
{
    public function __construct(
        private CacheInvalidatorRepository $cacheInvalidatorRepository,
        private ?LoggerInterface $logger = null,
    ) {}

    public function invalidate(SeriesIdentity $series): void
    {
        $this->logger?->info('Invalidating seasons cache for series {series}', [
            'series' => $series->ref()->value(),
        ]);

        $this->cacheInvalidatorRepository->invalidate(Context::SEASONS->value, $series->ref()->value());
    }
}
