<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnRaceLapScrappingSuccess;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\RaceLapScrappingSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class RaceLapScrappingSuccessLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(RaceLapScrappingSuccessEvent $event): void
    {
        $this->logger->notice("Finished scrapping classification for event {$event->eventId()}");
    }
}
