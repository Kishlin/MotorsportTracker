<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnRaceLapScrappingFailure;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\RaceLapScrappingFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class RaceLapScrappingFailureLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(RaceLapScrappingFailureEvent $event): void
    {
        $carPosition = $event->carPosition();

        $this->logger->error("Failed to scrap car position for entry uuid {$carPosition['entryUuid']}.", $carPosition);
    }
}
