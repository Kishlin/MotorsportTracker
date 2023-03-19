<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnSeasonAnalyticsScrappingFailure;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasonAnalytics\SeasonAnalyticsScrappingFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class SeasonAnalyticsScrappingFailureLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SeasonAnalyticsScrappingFailureEvent $event): void
    {
        $standingData = $event->standing();

        $this->logger->error("Failed to scrap standing for {$standingData['driver']['name']}.", $standingData);
    }
}
