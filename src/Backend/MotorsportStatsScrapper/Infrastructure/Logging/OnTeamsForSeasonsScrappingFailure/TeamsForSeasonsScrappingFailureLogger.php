<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnTeamsForSeasonsScrappingFailure;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapTeamsForSeason\TeamsForSeasonsScrappingFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class TeamsForSeasonsScrappingFailureLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(TeamsForSeasonsScrappingFailureEvent $event): void
    {
        $standingData = $event->standing();

        $this->logger->error("Failed to scrap standing for {$standingData['team']['name']}.", $standingData);
    }
}
