<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Logging\OnDriverStandingUpdated;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\StandingUpdatedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class StandingUpdateLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(StandingUpdatedEvent $event): void
    {
        $standingType = $event->standingType()->toString();

        $context = [
            'season' => $event->season()->value(),
            'sandee' => $event->standee()->value(),
            'type'   => $standingType,
        ];

        $this->logger->info("Updated {$standingType} standing record.", $context);
    }
}
