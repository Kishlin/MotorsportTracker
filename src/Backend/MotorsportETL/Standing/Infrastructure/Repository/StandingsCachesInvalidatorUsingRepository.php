<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Infrastructure\Repository;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector\CacheInvalidatorRepository;
use Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings\StandingsCachesInvalidator;
use Psr\Log\LoggerInterface;

final readonly class StandingsCachesInvalidatorUsingRepository implements StandingsCachesInvalidator
{
    public function __construct(
        private CacheInvalidatorRepository $cacheInvalidatorRepository,
        private ?LoggerInterface $logger = null,
    ) {}

    public function invalidate(SeasonIdentity $season): void
    {
        $this->logger?->info('Invalidating standings caches for season {season}', [
            'season' => $season->ref(),
        ]);

        $this->cacheInvalidatorRepository->invalidate(Context::STANDINGS->value, $season->ref());
        $this->cacheInvalidatorRepository->invalidate(Context::STANDINGS_TEAMS->value, $season->ref());
        $this->cacheInvalidatorRepository->invalidate(Context::STANDINGS_DRIVERS->value, $season->ref());
        $this->cacheInvalidatorRepository->invalidate(Context::STANDINGS_CONSTRUCTORS->value, $season->ref());
    }
}
