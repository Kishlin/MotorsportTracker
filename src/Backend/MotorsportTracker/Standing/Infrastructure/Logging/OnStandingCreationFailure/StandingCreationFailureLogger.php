<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Logging\OnStandingCreationFailure;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\StandingCreationFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class StandingCreationFailureLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(StandingCreationFailureEvent $event): void
    {
        $standingType = $event->standingType()->toString();

        $context = [
            'season' => $event->season()->value(),
            'sandee' => $event->standee()->value(),
            'type'   => $standingType,
        ];

        $this->logger->error("Failed to create {$standingType} standing record.", $context);
    }
}
